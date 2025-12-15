<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Cache System</title>
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
        .customers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        .customer-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .customer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }
        .customer-name {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .customer-info {
            color: #666;
            font-size: 0.95em;
            margin: 8px 0;
        }
        .customer-info strong {
            color: #444;
        }
        .btn-view {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-view:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Customers</h1>
            <p class="subtitle">Browse all customers and their orders</p>
            <div class="nav">
                <a href="{{ route('customers.index') }}">👥 Customers</a>
                <a href="{{ route('orders.index') }}">📦 Orders</a>
            </div>
        </div>

        <div class="customers-grid">
            @foreach($customers as $customer)
            <div class="customer-card">
                <div class="customer-name">{{ $customer->customerName }}</div>
                <div class="customer-info"><strong>Contact:</strong> {{ $customer->contactFirstName }} {{ $customer->contactLastName }}</div>
                <div class="customer-info"><strong>Phone:</strong> {{ $customer->phone }}</div>
                <div class="customer-info"><strong>City:</strong> {{ $customer->city }}, {{ $customer->country }}</div>
                <div class="customer-info"><strong>Customer #:</strong> {{ $customer->customerNumber }}</div>
                <a href="{{ route('customer.orders', $customer->customerNumber) }}" class="btn-view">View Orders</a>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
