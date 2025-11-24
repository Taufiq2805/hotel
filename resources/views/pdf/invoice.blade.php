<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>
<body>
    <h2>Invoice Reservasi</h2>

    <p><strong>Nama Tamu:</strong> {{ $reservasi->nama_tamu }}</p>
    <p><strong>Kamar:</strong> {{ $reservasi->kamar->tipe->nama ?? '-' }}</p>
    <p><strong>Tanggal Check-in:</strong> {{ $reservasi->tanggal_checkin }}</p>
    <p><strong>Tanggal Check-out:</strong> {{ $reservasi->tanggal_checkout }}</p>

    <hr>

    <p><strong>Total Pembayaran:</strong> Rp {{ number_format($reservasi->total, 0, ',', '.') }}</p>
</body>
</html>
