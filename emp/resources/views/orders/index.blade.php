<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Cache System</title>
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
        .subtitle {
            color: #666;
            font-size: 1.1em;
        }
        .nav {
            margin-top: 20px;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background 0.3s;
        }
        .nav a:hover {
            background: #5568d3;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .orders-grid {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .status.processing {
            background: #fff3cd;
            color: #856404;
        }
        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            display: inline-block;
            margin-right: 5px;
        }
        .btn-view {
            background: #007bff;
            color: white;
        }
        .btn-view:hover {
            background: #0056b3;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Orders Management</h1>
            <p class="subtitle">View and manage all orders in the system</p>
            <div class="nav">
                <a href="{{ route('customers.index') }}">👥 Customers</a>
                <a href="{{ route('orders.index') }}">📦 Orders</a>
                <a href="{{ route('orders.create') }}">➕ New Order</a>
            </div>
        </div>

        @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif

        <div class="orders-grid">
            <h2 style="margin-bottom: 20px; color: #333;">All Orders ({{ $orders->total() }})</h2>
            
            @if($orders->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Required Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->orderNumber }}</strong></td>
                        <td>{{ $order->customer->customerName ?? 'N/A' }}</td>
                        <td>{{ $order->orderDate }}</td>
                        <td>{{ $order->requiredDate }}</td>
                        <td>
                            <span class="status {{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->orderNumber) }}" class="btn btn-view">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $orders->links() }}
            </div>
            @else
            <p style="text-align: center; color: #666; padding: 40px 0;">
                No orders found. <a href="{{ route('orders.create') }}">Create your first order</a>
            </p>
            @endif
        </div>
    </div>
</body>
</html>
