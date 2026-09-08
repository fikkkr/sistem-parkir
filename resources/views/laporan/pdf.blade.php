<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan Parkir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        
        .summary-card {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        
        .summary-card p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        
        .table-container {
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SYSTEM PARKIR</h1>
        <p class="subtitle">Laporan Pendapatan Parkir</p>
    </div>
    
    <div class="summary">
        <div class="summary-card">
            <h3>Rentang Tanggal</h3>
            <p>{{ \Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_selesai)->format('d/m/Y') }}</p>
        </div>
        
        <div class="summary-card">
            <h3>Total Transaksi</h3>
            <p>{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
        </div>
        
        <div class="summary-card">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($totalPendapatan, 2, ',', '.') }}</p>
        </div>
    </div>
    
    <div class="table-container">
        <h2 style="margin-bottom: 15px;">Detail Transaksi</h2>
        
        @if($transaksiSelesai->isEmpty())
            <div class="no-data">
                Tidak ada transaksi dalam rentang tanggal yang dipilih
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Kode Karcis</th>
                        <th>Plat Nomor</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Durasi</th>
                        <th>Total Bayar</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiSelesai as $transaksi)
                        <tr>
                            <td>{{ $transaksi->kode_karcis }}</td>
                            <td>{{ $transaksi->plat_nomor }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaksi->durasi_jam }} jam</td>
                            <td>Rp {{ number_format($transaksi->total_bayar, 2, ',', '.') }}</td>
                            <td>{{ $transaksi->pintuKeluar?->user?->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>