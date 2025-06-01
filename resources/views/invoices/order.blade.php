<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->invoice_no }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            color: #333;
        }

        h1 {
            text-align: right;
            letter-spacing: 4px;
        }

        .section {
            margin-bottom: 30px;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .total-section td {
            font-weight: bold;
            border: none;
        }

        .signature {
            margin-top: 80px;
            text-align: right;
        }
    </style>
</head>

<body>

    <h1>INVOICE</h1>

    <div class="section flex">
        <div>
            <strong>ISSUED TO:</strong><br>
            {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
            {{ $order->address->address }}<br>
            {{ $order->address->city }},
            {{ $order->address->state }},
            {{ $order->address->country }}
        </div>
        <div>
            <strong>INVOICE NO:</strong> {{ $order->id }}<br>
            <strong>DATE:</strong> {{ $order->created_at }}<br>
        </div>
    </div>

    <!-- <div class="section">
        <strong>PAY TO:</strong><br>
        Borcele Bank<br>
        Account Name: Adeline Palmerston<br>
        Account No.: 0123 4567 8901
    </div> -->

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->qnt }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-section">
        <tr>
            <td colspan="4" style="text-align: right">SubTotal</td>
            <td>${{ number_format($order->grand_total, 2) }}</td>
        </tr>
    </table>

    <div class="signature">
       <em>{{ config('app.name') }}</em>
    </div>

</body>

</html>