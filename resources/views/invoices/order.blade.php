<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Invoice - Order #{{ $order->id }}</h2>

    <p><strong>Name:</strong> {{ $order->user?->name ?? 'N/A'}}</p>
    <p><strong>Email:</strong> {{ $order->user?->email ?? 'N/A'}}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>

    <h4>Items:</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items ?? [] as $item)
            <tr>
                <td>{{ $item?->name ?? 'Product' }}</td>
                <td>{{ $item?->sku ?? 'Product' }}</td>
                <td>{{ $item?->qnt ?? 'Product' }}</td>
                <td>₹{{ number_format($item->price, 2) }}</td>
                <td>₹{{ number_format($item->price * $item->qnt, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> ₹{{ number_format($order->grand_total, 2) }}</p>
</body>

</html>