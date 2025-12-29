<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
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
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-info {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .order-info-row:last-child {
            border-bottom: none;
        }
        .order-info-label {
            color: #6c757d;
        }
        .order-info-value {
            font-weight: bold;
        }
        .section {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .section h3 {
            margin-top: 0;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.1em;
        }
        .total-row td {
            border-top: 2px solid #333;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
            background-color: #ffc107;
            color: #000;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 0.9em;
        }
        .thank-you {
            text-align: center;
            font-size: 1.2em;
            color: #28a745;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Confirmed!</h1>
        <p>Thank you for your order</p>
    </div>

    <div class="content">
        <p class="thank-you">Hi {{ $user->name }}, your order has been received!</p>

        <div class="order-info">
            <div class="order-info-row">
                <span class="order-info-label">Order Number:</span>
                <span class="order-info-value">{{ $order->order_number }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Order Date:</span>
                <span class="order-info-value">{{ $order->created_at->format('F j, Y g:i A') }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Payment Method:</span>
                <span class="order-info-value">{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Status:</span>
                <span class="status-badge">{{ $order->status->label() }}</span>
            </div>
        </div>

        <div class="section">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product Unavailable' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if($order->shipping_cost > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;">Shipping:</td>
                        <td>${{ number_format($order->shipping_cost, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total:</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->notes)
        <div class="section">
            <h3>Order Notes</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <div class="section">
            <h3>What's Next?</h3>
            <p>We'll send you another email when your order ships. You can track your order status anytime by visiting your account.</p>
        </div>
    </div>

    <div class="footer">
        <p>Questions about your order? Contact our support team.</p>
        <p>{{ config('app.name') }} - {{ now()->format('Y') }}</p>
    </div>
</body>
</html>
