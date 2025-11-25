<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            color: #333;
        }
        h2 { 
            text-align: center; 
            margin-bottom: 5px;
        }
        .section-title {
            margin-top: 25px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
        }

        /* GRID 2 KOLOM – 3 BARIS (3 KIRI, 3 KANAN) */
        .info-grid {
            display: grid;
            grid-template-columns: 50% 50%;
            row-gap: 8px;
            column-gap: 30px;
            margin-top: 10px;
        }

        .info-item {
            display: flex;
            gap: 10px;
        }

        .label {
            font-weight: bold;
        }

        .value {
            display: inline;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th { 
            padding: 10px; 
            border: 1px solid #ccc; 
            background: #f2f2f2;
            text-align: left;
        }
        td { 
            padding: 8px; 
            border: 1px solid #ccc; 
        }
        .total-row td {
            font-size: 16px;
            font-weight: bold;
            background: #fafafa;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<h2>INVOICE RESERVASI</h2>

<div class="section-title">Informasi Tamu</div>

@php
    $checkin  = \Carbon\Carbon::parse($reservasi->tanggal_checkin);
    $checkout = \Carbon\Carbon::parse($reservasi->tanggal_checkout);
    $lama     = $checkin->diffInDays($checkout);
@endphp

<!-- GRID 2 KOLOM x 3 BARIS = 6 ITEM (3 KIRI, 3 KANAN) -->
<div class="info-grid">

    <!-- BARIS 1 KIRI -->
    <div class="info-item">
        <span class="label">Nama Tamu:</span>
        <span class="value">{{ $reservasi->nama_tamu }}</span>
    </div>

    <!-- BARIS 1 KANAN -->
    <div class="info-item">
        <span class="label">Check-in:</span>
        <span class="value">{{ $reservasi->tanggal_checkin }}</span>
    </div>

    <!-- BARIS 2 KIRI -->
    <div class="info-item">
        <span class="label">No. Kamar:</span>
        <span class="value">{{ $reservasi->kamar->nomor_kamar ?? '-' }}</span>
    </div>

    <!-- BARIS 2 KANAN -->
    <div class="info-item">
        <span class="label">Check-out:</span>
        <span class="value">{{ $reservasi->tanggal_checkout }}</span>
    </div>

    <!-- BARIS 3 KIRI -->
    <div class="info-item">
        <span class="label">Tipe Kamar:</span>
        <span class="value">{{ $reservasi->kamar->tipe->nama ?? '-' }}</span>
    </div>

    <!-- BARIS 3 KANAN -->
    <div class="info-item">
        <span class="label">Lama Menginap:</span>
        <span class="value">{{ $lama }} malam</span>
    </div>

</div>


<div class="section-title">Detail Pembayaran</div>

<table>
    <tr>
        <th>Deskripsi</th>
        <th class="text-right">Total</th>
    </tr>

    <tr>
        <td>Biaya Kamar ({{ $lama }} malam)</td>
        <td class="text-right">
            Rp {{ number_format($reservasi->kamar->tipe->harga * $lama, 0, ',', '.') }}
        </td>
    </tr>

    @foreach($reservasi->makanans as $m)
    <tr>
        <td>Makanan: {{ $m->nama }}</td>
        <td class="text-right">
            Rp {{ number_format($m->harga, 0, ',', '.') }}
        </td>
    </tr>
    @endforeach

    <tr class="total-row">
        <td>Total Pembayaran</td>
        <td class="text-right">
            Rp {{ number_format($reservasi->total ?? 0, 0, ',', '.') }}
        </td>
    </tr>
</table>

</body>
</html>