<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function show($customerNumber)
    {
        $customer = Customer::findOrFail($customerNumber);
        return view('customers.show', compact('customer'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customerNumber' => 'required|unique:customers,customerNumber',
            'customerName' => 'required',
            'contactLastName' => 'required',
            'contactFirstName' => 'required',
            'phone' => 'required',
            'addressLine1' => 'required',
            'city' => 'required',
            'country' => 'required'
        ]);

        Customer::create([
            'customerNumber' => $request->input('customerNumber'),
            'customerName' => $request->input('customerName'),
            'contactLastName' => $request->input('contactLastName'),
            'contactFirstName' => $request->input('contactFirstName'),
            'phone' => $request->input('phone'),
            'addressLine1' => $request->input('addressLine1'),
            'addressLine2' => $request->input('addressLine2'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postalCode' => $request->input('postalCode'),
            'country' => $request->input('country'),
            'salesRepEmployeeNumber' => $request->input('salesRepEmployeeNumber'),
            'creditLimit' => $request->input('creditLimit')
        ]);

        return redirect()->route('customers.index')
                        ->with('success', 'Customer created successfully!');
    }

    public function edit($customerNumber)
    {
        $customer = Customer::findOrFail($customerNumber);
        return view('customers.edit', compact('customer'));
    }

    // Show orders for a customer
    public function showOrders($customerNumber)
    {
        $customer = Customer::with('orders')->findOrFail($customerNumber);
        return view('customers.orders.index', compact('customer'));
    }

    public function createOrder($customerNumber)
    {
        $customer = Customer::findOrFail($customerNumber);
        return view('customers.orders.create', compact('customer'));
    }

    public function editOrder($customerNumber, $orderNumber)
    {
        $customer = Customer::findOrFail($customerNumber);
        $order = Order::where('orderNumber', $orderNumber)->where('customerNumber', $customerNumber)->firstOrFail();
        return view('customers.orders.edit', compact('customer', 'order'));
    }

    public function storeOrder(Request $request, $customerNumber)
    {
        $customer = Customer::findOrFail($customerNumber);
        $request->validate([
            'orderNumber' => 'required',
            'orderDate' => 'required',
            'requiredDate' => 'required',
            'status' => 'required'
        ]);

        $order = new Order([
            'orderNumber' => $request->input('orderNumber'),
            'orderDate' => $request->input('orderDate'),
            'requiredDate' => $request->input('requiredDate'),
            'shippedDate' => $request->input('shippedDate') ?? '1970-01-01',
            'status' => $request->input('status'),
            'comments' => $request->input('comments') ?? '',
            'customerNumber' => $customerNumber
        ]);

        $orderNumber = $order->orderNumber;

        $customer->orders()->save($order);

        // Call produce script asynchronously
        $this->runProduceScript($customer->customerNumber, $orderNumber);

        return redirect()->route('customer.orders', ['customerNumber' => $customer->customerNumber])
                        ->with('success', 'Order created successfully!');
    }

    public function updateOrder(Request $request, $customerNumber, $orderNumber)
    {
        $request->validate([
            'orderDate' => 'required',
            'requiredDate' => 'required',
            'status' => 'required'
        ]);

        $order = Order::where('orderNumber', $orderNumber)->where('customerNumber', $customerNumber)->firstOrFail();
        $order->orderDate = $request->input('orderDate');
        $order->requiredDate = $request->input('requiredDate');
        $order->shippedDate = $request->input('shippedDate') ?? $order->shippedDate;
        $order->status = $request->input('status');
        $order->comments = $request->input('comments') ?? $order->comments;
        $order->save();

        // Notify producer about the update
        $this->runProduceScript($customerNumber, $orderNumber);

        return redirect()->route('customer.orders', ['customerNumber' => $customerNumber])
                        ->with('success', 'Order updated successfully!');
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
    }
}
