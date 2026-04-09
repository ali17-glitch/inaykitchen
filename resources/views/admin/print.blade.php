<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pesanan - Inay Kitchen</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; font-size: 14px; }
        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px dashed #000; padding-bottom: 15px; }
        .order-card { border: 1px solid #000; padding: 15px; margin-bottom: 20px; page-break-inside: avoid; }
        .order-meta { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;}
        .items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items th, .items td { text-align: left; padding: 5px; border-bottom: 1px dashed #ccc; }
        .total { font-weight: bold; font-size: 1.2em; text-align: right; margin-top: 10px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-header">
        <h1>INAY KITCHEN</h1>
        <p>Laporan Rekap Pesanan</p>
        <button class="no-print" onclick="window.print()" style="padding: 10px 20px; font-size:16px; cursor:pointer;">Cetak Sekarang</button>
    </div>

    @foreach($orders as $order)
    <div class="order-card">
        <div class="order-meta">
            <div>
                <strong>ID Pesanan:</strong> #{{ $order->id }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                <strong>Pembayaran:</strong> {{ $order->payment_method ?? 'COD' }}
            </div>
            <div style="text-align: right;">
                <strong>Pelanggan:</strong> {{ $order->customer_name }}<br>
                <strong>WA:</strong> {{ $order->customer_whatsapp }}
            </div>
        </div>
        <strong>Alamat:</strong> {{ $order->address }}
        
        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($order->order_details))
                    @foreach($order->order_details as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="total">
            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>
    </div>
    @endforeach
</body>
</html>
