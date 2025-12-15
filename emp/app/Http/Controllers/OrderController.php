<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index()
    {
        $orders = Order::with('customer')->orderBy('orderDate', 'desc')->paginate(20);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('orders.create', compact('customers'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orderNumber' => 'required|unique:orders,orderNumber',
            'orderDate' => 'required|date',
            'requiredDate' => 'required|date',
            'status' => 'required',
            'customerNumber' => 'required|exists:customers,customerNumber'
        ]);

        $order = Order::create([
            'orderNumber' => $request->input('orderNumber'),
            'orderDate' => $request->input('orderDate'),
            'requiredDate' => $request->input('requiredDate'),
            'shippedDate' => $request->input('shippedDate') ?? null,
            'status' => $request->input('status'),
            'comments' => $request->input('comments') ?? '',
            'customerNumber' => $request->input('customerNumber')
        ]);

        // Trigger produce script
        $this->runProduceScript($request->input('customerNumber'), $request->input('orderNumber'));

        return redirect()->route('orders.index')
                        ->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified order.
     */
    public function show($orderNumber)
    {
        $order = Order::with('customer')->where('orderNumber', $orderNumber)->firstOrFail();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($orderNumber)
    {
        $order = Order::with('customer')->where('orderNumber', $orderNumber)->firstOrFail();
        $customers = Customer::all();
        return view('orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $orderNumber)
    {
        $order = Order::where('orderNumber', $orderNumber)->firstOrFail();

        $request->validate([
            'orderDate' => 'required|date',
            'requiredDate' => 'required|date',
            'status' => 'required',
            'customerNumber' => 'required|exists:customers,customerNumber'
        ]);

        $order->update([
            'orderDate' => $request->input('orderDate'),
            'requiredDate' => $request->input('requiredDate'),
            'shippedDate' => $request->input('shippedDate') ?? $order->shippedDate,
            'status' => $request->input('status'),
            'comments' => $request->input('comments') ?? $order->comments,
            'customerNumber' => $request->input('customerNumber')
        ]);

        return redirect()->route('orders.index')
                        ->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($orderNumber)
    {
        $order = Order::where('orderNumber', $orderNumber)->firstOrFail();
        $order->delete();

        return redirect()->route('orders.index')
                        ->with('success', 'Order deleted successfully!');
    }
    protected function runProduceScript($customerNumber, $orderNumber)
    {
        // Use Symfony Process to invoke the python producer script
        try {
            $python = 'python3';
            $script = base_path('produce.py');
            $process = new Process([$python, $script, (string)$customerNumber, (string)$orderNumber]);
            $process->setTimeout(10);
            
            // Run synchronously to capture output
            $process->run();
            
            // Log output for debugging
            if ($process->isSuccessful()) {
                logger()->info('produce.py executed successfully', [
                    'output' => $process->getOutput(),
                    'customer' => $customerNumber,
                    'order' => $orderNumber
                ]);
            } else {
                logger()->error('produce.py failed', [
                    'exit_code' => $process->getExitCode(),
                    'output' => $process->getOutput(),
                    'error' => $process->getErrorOutput(),
                    'customer' => $customerNumber,
                    'order' => $orderNumber
                ]);
            }
        } catch (\Exception $e) {
            // log the error but do not break the user flow
            logger()->error('Failed to start produce.py: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }}
