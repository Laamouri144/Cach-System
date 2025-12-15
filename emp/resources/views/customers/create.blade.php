<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Customer</title>
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
            max-width: 900px;
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
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
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
        h3 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Create New Customer</h1>
            <div class="nav">
                <a href="{{ route('customers.index') }}">← Back to Customers</a>
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

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <h3>Basic Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="customerNumber">Customer Number *</label>
                        <input type="number" id="customerNumber" name="customerNumber" value="{{ old('customerNumber') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="customerName">Customer Name *</label>
                        <input type="text" id="customerName" name="customerName" value="{{ old('customerName') }}" required>
                    </div>
                </div>

                <h3>Contact Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contactFirstName">Contact First Name *</label>
                        <input type="text" id="contactFirstName" name="contactFirstName" value="{{ old('contactFirstName') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="contactLastName">Contact Last Name *</label>
                        <input type="text" id="contactLastName" name="contactLastName" value="{{ old('contactLastName') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>

                <h3>Address</h3>

                <div class="form-group">
                    <label for="addressLine1">Address Line 1 *</label>
                    <input type="text" id="addressLine1" name="addressLine1" value="{{ old('addressLine1') }}" required>
                </div>

                <div class="form-group">
                    <label for="addressLine2">Address Line 2</label>
                    <input type="text" id="addressLine2" name="addressLine2" value="{{ old('addressLine2') }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="postalCode">Postal Code</label>
                        <input type="text" id="postalCode" name="postalCode" value="{{ old('postalCode') }}">
                    </div>

                    <div class="form-group">
                        <label for="country">Country *</label>
                        <input type="text" id="country" name="country" value="{{ old('country') }}" required>
                    </div>
                </div>

                <h3>Business Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="salesRepEmployeeNumber">Sales Rep Employee Number</label>
                        <input type="number" id="salesRepEmployeeNumber" name="salesRepEmployeeNumber" value="{{ old('salesRepEmployeeNumber') }}">
                    </div>

                    <div class="form-group">
                        <label for="creditLimit">Credit Limit</label>
                        <input type="number" step="0.01" id="creditLimit" name="creditLimit" value="{{ old('creditLimit') }}">
                    </div>
                </div>

                <button type="submit" class="btn-submit">✓ Create Customer</button>
            </form>
        </div>
    </div>
</body>
</html>
