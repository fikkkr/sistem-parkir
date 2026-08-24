<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $keluar->pintuMasuk->kode_karcis }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 10mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .receipt {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            @page {
                size: 80mm auto;
                margin: 0;
                padding: 0;
            }
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }

        .header h1 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .header p {
            font-size: 9px;
            margin: 0.5mm 0;
        }

        .section {
            margin-bottom: 2mm;
        }

        .section-title {
            font-weight: bold;
            font-size: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .row-label {
            flex: 0 0 60%;
        }

        .row-value {
            flex: 0 0 35%;
            text-align: right;
        }

        .divider {
            border-bottom: 2px dashed #000;
            margin: 2mm 0;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 1mm 0;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
            padding-top: 2mm;
            font-size: 10px;
            font-weight: bold;
        }

        .thank-you {
            margin-top: 2mm;
            text-align: center;
            font-style: italic;
            font-size: 10px;
        }

        .no-print {
            text-align: center;
            margin-top: 10mm;
            padding-top: 5mm;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        button {
            margin-top: 5mm;
            padding: 5mm 10mm;
            font-size: 11px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>SISTEM PARKIR</h1>
            <p>Struk Pembayaran</p>
        </div>

        <!-- Info Karcis -->
        <div class="section">
            <div class="row">
                <span class="row-label">Kode Karcis:</span>
                <span class="row-value"><strong>{{ $keluar->pintuMasuk->kode_karcis }}</strong></span>
            </div>
            <div class="row">
                <span class="row-label">Plat Nomor:</span>
                <span class="row-value"><strong>{{ $keluar->pintuMasuk->plat_nomor }}</strong></span>
            </div>
            @if ($keluar->pintuMasuk->masterTarif)
                <div class="row">
                    <span class="row-label">Jenis Kendaraan:</span>
                    <span class="row-value">{{ $keluar->pintuMasuk->masterTarif->jenis_kendaraan }}</span>
                </div>
            @endif
        </div>

        <!-- Waktu Masuk & Keluar -->
        <div class="section">
            <div class="row">
                <span class="row-label">Waktu Masuk:</span>
                <span class="row-value">{{ $keluar->pintuMasuk->waktu_masuk->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Waktu Keluar:</span>
                <span class="row-value">{{ $keluar->waktu_keluar->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Durasi Parkir:</span>
                <span class="row-value"><strong>{{ $keluar->durasi_jam }} Jam</strong></span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Biaya & Pembayaran -->
        <div class="section">
            <div class="row">
                <span class="row-label">Total Biaya:</span>
                <span class="row-value">Rp {{ number_format($keluar->total_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Uang Bayar:</span>
                <span class="row-value">Rp {{ number_format($keluar->uang_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="row total-row">
                <span class="row-label">Kembalian:</span>
                <span class="row-value">Rp {{ number_format($keluar->kembalian, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Status:</span>
                <span class="row-value"><strong>{{ $keluar->status_pembayaran }}</strong></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima Kasih & Selamat Jalan</p>
        </div>

        <div class="thank-you">
            <p>Semoga Perjalanan Anda Aman</p>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()">Cetak Struk</button>
        <button onclick="window.close()" style="background: #ef4444; margin-left: 5mm;">Tutup</button>
    </div>

    <script>
        // Auto-print pada saat halaman load
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
