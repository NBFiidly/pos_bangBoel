<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .product-detail {
            margin: 5px 0;
            padding-left: 10px;
        }
        .total-section {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        .total-label {
            font-size: 16px;
            font-weight: bold;
        }
        .total-amount {
            font-size: 20px;
            color: #4CAF50;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE TRANSAKSI</h1>
        <p>Bang Boel POS System</p>
    </div>

    @if($startDate || $endDate)
    <div class="periode">
        <strong>Periode:</strong> 
        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} 
        - 
        {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
    </div>
    @else
    <div class="periode">
        <strong>Semua Transaksi</strong>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Customer</th>
                <th style="width: 35%">Detail Produk</th>
                <th style="width: 20%; text-align: right;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i') }}</td>
                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                <td>
                    @foreach($order->orderdetails as $detail)
                    <div class="product-detail">
                        • {{ $detail->product->name ?? 'N/A' }} 
                        ({{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }})
                    </div>
                    @endforeach
                </td>
                <td style="text-align: right; font-weight: bold;">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">
                    Tidak ada transaksi dalam periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        <span class="total-label">TOTAL PENJUALAN: </span>
        <span class="total-amount">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Bang Boel POS System. All Rights Reserved.</p>
    </div>
</body>
</html>
