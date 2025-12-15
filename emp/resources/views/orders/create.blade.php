<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Order</title>
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
            max-width: 800px;
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
            font-size: 2em;
            margin-bottom: 10px;
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
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            font-family: inherit;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-submit {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #218838;
        }
        .errors {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 Create New Order</h1>
            <div class="nav">
                <a href="{{ route('orders.index') }}">← Back to Orders</a>
            </div>
        </div>

        <div class="form-section">
            @if($errors->any())
            <div class="errors">
                <strong>Please fix the following errors:</strong>
                <ul style="margin-top: 10px; margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="customerNumber">Customer *</label>
                    <select id="customerNumber" name="customerNumber" required>
                        <option value="">Select a customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->customerNumber }}" {{ old('customerNumber') == $customer->customerNumber ? 'selected' : '' }}>
                                {{ $customer->customerName }} (#{{ $customer->customerNumber }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="orderNumber">Order Number *</label>
                    <input type="text" id="orderNumber" name="orderNumber" value="{{ old('orderNumber', time()) }}" required>
                    <small style="color: #666;">Auto-generated timestamp or enter custom number</small>
                </div>

                <div class="form-group">
                    <label for="orderDate">Order Date *</label>
                    <input type="date" id="orderDate" name="orderDate" value="{{ old('orderDate', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label for="requiredDate">Required Date *</label>
                    <input type="date" id="requiredDate" name="requiredDate" value="{{ old('requiredDate', date('Y-m-d', strtotime('+7 days'))) }}" required>
                </div>

                <div class="form-group">
                    <label for="shippedDate">Shipped Date</label>
                    <input type="date" id="shippedDate" name="shippedDate" value="{{ old('shippedDate') }}">
                    <small style="color: #666;">Leave empty if not yet shipped</small>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="In Process" {{ old('status', 'In Process') == 'In Process' ? 'selected' : '' }}>In Process</option>
                        <option value="Shipped" {{ old('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Resolved" {{ old('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="On Hold" {{ old('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="Disputed" {{ old('status') == 'Disputed' ? 'selected' : '' }}>Disputed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea id="comments" name="comments" rows="4">{{ old('comments') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">✓ Create Order</button>
            </form>
        </div>
    </div>
</body>
</html>
