<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
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
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .summary-box {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .summary-box .value {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .summary-box .label {
            color: #6c757d;
            font-size: 0.9em;
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
            border-bottom: 2px solid #007bff;
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
        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-processing { background-color: #17a2b8; color: #fff; }
        .status-shipped { background-color: #007bff; color: #fff; }
        .status-delivered { background-color: #28a745; color: #fff; }
        .status-cancelled { background-color: #dc3545; color: #fff; }
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
        <h1>Daily Sales Report</h1>
        <p>{{ $reportDate->format('l, F j, Y') }}</p>
    </div>

    <div class="content">
        <div class="summary-grid">
            <div class="summary-box">
                <div class="value">{{ $reportData['total_orders'] }}</div>
                <div class="label">Total Orders</div>
            </div>
            <div class="summary-box">
                <div class="value">${{ number_format($reportData['total_revenue'], 2) }}</div>
                <div class="label">Total Revenue</div>
            </div>
            <div class="summary-box">
                <div class="value">{{ $reportData['total_items_sold'] }}</div>
                <div class="label">Items Sold</div>
            </div>
            <div class="summary-box">
                <div class="value">${{ number_format($reportData['average_order_value'], 2) }}</div>
                <div class="label">Average Order Value</div>
            </div>
        </div>

        @if(count($reportData['status_breakdown']) > 0)
        <div class="section">
            <h3>Order Status Breakdown</h3>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['status_breakdown'] as $status => $count)
                    <tr>
                        <td>
                            <span class="status-badge status-{{ strtolower($status) }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td>{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="section">
            <h3>Products Sold Today</h3>
            @if($reportData['product_sales']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Qty Sold</th>
                        <th>Revenue</th>
                        <th>Stock Left</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['product_sales'] as $sale)
                    <tr>
                        <td>{{ $sale->product->name ?? 'N/A' }}</td>
                        <td>{{ $sale->product->sku ?? 'N/A' }}</td>
                        <td>{{ $sale->total_quantity }}</td>
                        <td>${{ number_format($sale->total_revenue, 2) }}</td>
                        <td>{{ $sale->product->stock ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="no-data">No products were sold today.</p>
            @endif
        </div>

        <div class="section">
            <h3>Order Details</h3>
            @if($reportData['orders']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['orders'] as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="no-data">No orders were placed today.</p>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>This is an automated report from {{ config('app.name') }}</p>
        <p>Generated at {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>
</html>
