<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('orders.index');
});

// Development 1 routes (customers/orders)
Route::resource('customers', CustomerController::class);
Route::resource('orders', OrderController::class);

Route::get('/customers/{customerNumber}/orders', [CustomerController::class, 'showOrders'])->name('customer.orders');
Route::post('/customers/{customerNumber}/orders', [CustomerController::class, 'storeOrder'])->name('customer.orders.store');

Route::get('/customers/{customerNumber}/orders/create', [CustomerController::class, 'createOrder'])->name('customer.orders.create');
Route::get('/customers/{customerNumber}/orders/{orderNumber}/edit', [CustomerController::class, 'editOrder'])->name('customer.orders.edit');

Route::put('/customers/{customerNumber}/orders/{orderNumber}', [CustomerController::class, 'updateOrder'])->name('customer.orders.update');

// Dev helper route to create an order for an existing customer and trigger the producer
Route::get('/dev/test-create-order/{customerNumber}', function ($customerNumber) {
    $customer = App\Models\Customer::find($customerNumber);
    if (!$customer) {
        return response()->json(['error' => 'customer not found'], 404);
    }

    $orderNumber = (string) time();
    $order = App\Models\Order::create([
        'orderNumber' => $orderNumber,
        'orderDate' => date('Y-m-d'),
        'requiredDate' => date('Y-m-d', strtotime('+7 days')),
        'shippedDate' => '1970-01-01',
        'status' => 'In Process',
        'comments' => 'Test order',
        'customerNumber' => $customer->customerNumber
    ]);

    // call produce.py
    $python = config('app.python_path', 'python');
    $script = base_path('produce.py');
    // Attempt a background execution on *nix; on Windows will fallback to synchronous exec
    $cmd = escapeshellarg($python) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($customer->customerNumber) . ' ' . escapeshellarg($orderNumber) . ' > /dev/null 2>&1 &';
    try {
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            exec("$python $script $customer->customerNumber $orderNumber", $output, $ret);
        } else {
            exec($cmd, $output, $ret);
        }
    } catch (\Throwable $e) {
        \Log::error('Failed to execute produce.py: ' . $e->getMessage());
    }

    return response()->json(['customer' => $customer->customerNumber, 'order' => $orderNumber]);
});
