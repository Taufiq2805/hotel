<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h3, h4 {
            text-align: center;
            margin-bottom: 5px;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h3>LAPORAN KEUANGAN</h3>

    @if(count($pemasukan))
        <h4 class="section-title">Pemasukan - {{ \Carbon\Carbon::createFromFormat('Y-m', $request->bulan)->translatedFormat('F Y') }}</h4>
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="15%">Tanggal</th>
                    <th>Nama Tamu</th>
                    <th>Kamar</th>
                    <th class="text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pemasukan as $item)
                    <tr>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->reservasi->nama_tamu ?? '-' }}</td>
                        <td>{{ $item->reservasi->kamar->nomor_kamar ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($item->reservasi->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(count($pengeluaran))
        <h4 class="section-title">Pengeluaran</h4>
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="15%">Tanggal</th>
                    <th>User</th>
                    <th>Barang</th>
                    <th class="text-right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengeluaran as $item)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
