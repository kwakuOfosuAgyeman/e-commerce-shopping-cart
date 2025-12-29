<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .product-details {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .product-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-details td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .product-details td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .stock-warning {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.2em;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Low Stock Alert</h1>
    </div>

    <div class="content">
        <div class="alert-box">
            <strong>Attention Required:</strong> A product in your inventory has fallen below the low stock threshold.
        </div>

        <div class="product-details">
            <h3>Product Details</h3>
            <table>
                <tr>
                    <td>Product Name:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td>SKU:</td>
                    <td>{{ $product->sku ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Current Stock:</td>
                    <td class="stock-warning">{{ $currentStock }} units</td>
                </tr>
                <tr>
                    <td>Low Stock Threshold:</td>
                    <td>{{ $threshold }} units</td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                </tr>
            </table>
        </div>

        <p>Please restock this product as soon as possible to avoid stockouts and lost sales.</p>
    </div>

    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}</p>
        <p>Generated at {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>
</html>
