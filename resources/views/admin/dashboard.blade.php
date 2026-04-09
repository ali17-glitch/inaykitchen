<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inay Kitchen - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff4757;
            --secondary: #2ed573;
            --dark: #121212;
            --dark-card: #1e1e1e;
            --text-main: #ffffff;
            --text-muted: #a4b0be;
            --accent: #ffa502;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--dark); color: var(--text-main); display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 250px; background-color: var(--dark-card); padding: 20px; border-right: 1px solid rgba(255,255,255,0.05); }
        .sidebar-brand { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 40px; display: block; text-decoration: none;}
        .sidebar-brand span { color: var(--text-main); }
        .nav-item { display: block; padding: 15px; background: rgba(255,71,87,0.1); color: var(--primary); font-weight: 600; border-radius: 10px; text-decoration: none; margin-bottom: 10px; }
        .nav-item:hover { background: var(--primary); color: white; transition: 0.3s; }
        .nav-item.active { background: var(--primary); color: white; }

        /* Main Content */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-top h1 { font-size: 2rem; }
        .btn-print { background: var(--secondary); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.3s;}
        .btn-print:hover { background: #26b15f; transform: translateY(-2px); }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--dark-card); padding: 25px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); }
        .stat-title { color: var(--text-muted); font-size: 1rem; margin-bottom: 10px; }
        .stat-value { font-size: 2.5rem; font-weight: 800; color: var(--primary); }
        .stat-card.green .stat-value { color: var(--secondary); }
        .stat-card.yellow .stat-value { color: var(--accent); }

        /* Table Area */
        .table-container { background: var(--dark-card); border-radius: 15px; padding: 20px; border: 1px solid rgba(255,255,255,0.05); overflow-x: auto; }
        .table-header { margin-bottom: 20px; font-size: 1.2rem; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: var(--text-muted); border-bottom: 1px solid rgba(255,255,255,0.1); font-weight: 600; }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: top; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .customer-info strong { font-size: 1.1rem; color: var(--accent); display: block; margin-bottom: 5px;}
        .customer-info a { color: var(--secondary); text-decoration: none; }
        
        .order-items { margin: 0; padding-left: 20px; color: var(--text-muted); font-size: 0.9rem; }
        
        /* Form & Status */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; background: #333; color: white; display: inline-block;}
        .badge-pending { background: rgba(255, 165, 2, 0.2); color: var(--accent); }
        .badge-proses { background: rgba(52, 152, 219, 0.2); color: #3498db; }
        .badge-selesai { background: rgba(46, 213, 115, 0.2); color: var(--secondary); }
        .badge-batal { background: rgba(255, 71, 87, 0.2); color: var(--primary); }

        .status-form { display: flex; gap: 5px; align-items: center; margin-top: 10px; }
        .status-select { background: rgba(0,0,0,0.5); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 5px; border-radius: 5px; font-family: 'Outfit'; outline: none;}
        .btn-update { background: var(--primary); border: none; color: white; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-weight: bold; font-family: 'Outfit'; }
        .btn-update:hover { background: #ff6b81; }

        .alert-success { background: rgba(46, 213, 115, 0.2); color: var(--secondary); padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: bold;}
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="/" class="sidebar-brand">Inay<span>Kitchen</span></a>
        <a href="/admin" class="nav-item active">📊 Dashboard</a>
        <a href="/" class="nav-item" style="background:transparent; color:var(--text-muted);">🌐 Menuju Ke Website</a>
    </aside>

    <main class="main-content">
        <div class="header-top">
            <h1>Dashboard Admin</h1>
            <a href="{{ route('admin.orders.print') }}" target="_blank" class="btn-print">🖨️ Cetak Rekap Pesanan</a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Total Pesanan Masuk</div>
                <div class="stat-value">{{ $totalOrders }}</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-title">Pesanan Menunggu (Pending)</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-title">Pendapatan (Selesai)</div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">Daftar Terbaru Pesanan</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan & Kontak</th>
                        <th>Detail Menu</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            #{{ $order->id }}<br>
                            <span class="badge" style="margin-top:5px; background: rgba(255,255,255,0.1); font-size: 0.75rem;">{{ $order->payment_method ?? 'COD' }}</span>
                        </td>
                        <td class="customer-info">
                            <strong>{{ $order->customer_name }}</strong>
                            💬 <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer_whatsapp) }}" target="_blank">{{ $order->customer_whatsapp }}</a><br>
                            <span style="font-size:0.85rem; color: var(--text-muted); margin-top: 5px; display:block;">📍 {{ $order->address }}</span>
                        </td>
                        <td>
                            <ul class="order-items">
                                @if(is_array($order->order_details))
                                    @foreach($order->order_details as $item)
                                        <li>{{ $item['name'] }} ({{ $item['quantity'] }}x)</li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td style="font-weight: 800; font-size: 1.1rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeClass = 'badge';
                                if($order->status == 'Pending') $badgeClass .= ' badge-pending';
                                elseif($order->status == 'Proses') $badgeClass .= ' badge-proses';
                                elseif($order->status == 'Selesai') $badgeClass .= ' badge-selesai';
                                elseif($order->status == 'Batal') $badgeClass .= ' badge-batal';
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $order->status }}</span>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="status-form">
                                @csrf
                                <select name="status" class="status-select">
                                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Proses" {{ $order->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                                <button type="submit" class="btn-update">Ubah</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">Belum ada pesanan yang masuk hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
