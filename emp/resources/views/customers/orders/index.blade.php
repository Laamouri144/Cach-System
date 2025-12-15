<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customer->customerName }} - Orders</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .customer-info {
            color: #666;
            font-size: 1.1em;
            margin: 10px 0;
        }
        .nav {
            margin-top: 20px;
        }
        .nav a, .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background 0.3s;
        }
        .nav a:hover, .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .orders-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .status.shipped {
            background: #d4edda;
            color: #155724;
        }
        .status.in.process {
            background: #fff3cd;
            color: #856404;
        }
        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .status.resolved, .status.on.hold {
            background: #d1ecf1;
            color: #0c5460;
        }
        .no-orders {
            text-align: center;
            color: #666;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $customer->customerName }}</h1>
            <div class="customer-info"><strong>Contact:</strong> {{ $customer->contactFirstName }} {{ $customer->contactLastName }}</div>
            <div class="customer-info"><strong>Phone:</strong> {{ $customer->phone }}</div>
            <div class="customer-info"><strong>Location:</strong> {{ $customer->city }}, {{ $customer->country }}</div>
            <div class="customer-info"><strong>Customer #:</strong> {{ $customer->customerNumber }}</div>
            
            <div class="nav">
                <a href="{{ route('customers.index') }}">← Back to Customers</a>
                <a href="{{ route('customer.orders.create', $customer->customerNumber) }}" class="btn-success">+ Create New Order</a>
            </div>
        </div>

        @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif

        <div class="orders-section">
            <h2 style="margin-bottom: 20px; color: #333;">Orders ({{ $customer->orders->count() }})</h2>
            
            @if($customer->orders->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Order Date</th>
                        <th>Required Date</th>
                        <th>Shipped Date</th>
                        <th>Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->orders as $order)
                    <tr>
                        <td><strong>#{{ $order->orderNumber }}</strong></td>
                        <td>{{ $order->orderDate }}</td>
                        <td>{{ $order->requiredDate }}</td>
                        <td>{{ $order->shippedDate ?: 'Not shipped' }}</td>
                        <td>
                            <span class="status {{ strtolower(str_replace(' ', '.', $order->status)) }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>{{ $order->comments ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="no-orders">
                No orders yet for this customer. <a href="{{ route('customer.orders.create', $customer->customerNumber) }}">Create the first order</a>
            </p>
            @endif
        </div>
    </div>
</body>
</html>
