<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pesanan Inay Kitchen</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #ff4757; }
        .header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-print { background-color: #2ed573; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-print:hover { background-color: #26b15f; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #ff4757; color: white; }
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; background: #fff3cd; color: #856404; }
        .detail-item { font-size: 0.9em; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-action">
            <h1>Daftar Pesanan Inay Kitchen</h1>
            <a href="{{ route('admin.orders.print') }}" target="_blank" class="btn-print">🖨️ Cetak Semua Pesanan</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Detail Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong><br>
                        📞 <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer_whatsapp) }}" target="_blank">{{ $order->customer_whatsapp }}</a><br>
                        📍 {{ $order->address }}
                    </td>
                    <td>
                        <ul style="margin:0; padding-left:20px;">
                            @if(is_array($order->order_details))
                                @foreach($order->order_details as $item)
                                    <li class="detail-item">{{ $item['name'] }} ({{ $item['quantity'] }}x) - Rp {{ number_format($item['price'], 0, ',', '.') }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                    <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                    <td><span class="badge">{{ ucfirst($order->status) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
