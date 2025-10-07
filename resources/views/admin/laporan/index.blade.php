@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Form Pemasukan -->
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <h5 class="mb-3">Pemasukan</h5>
                <label for="bulan">Pilih Bulan Pembayaran</label>
                <select name="bulan" id="bulan" class="form-control">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach ($bulanOptions as $item)
                        @php
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $item->bulan);
                        @endphp
                        <option value="{{ $item->bulan }}" {{ request('bulan') == $item->bulan ? 'selected' : '' }}>
                            {{ $date->translatedFormat('F Y') }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary mt-2">Laporan Pemasukan</button>
            </form>
        </div>

        <!-- Form Pengeluaran -->
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <h5 class="mb-3">Pengeluaran</h5>
                <div class="mb-2">
                    <label for="dari">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="mb-2">
                    <label for="sampai">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <button class="btn btn-primary">Laporan Pengeluaran</button>
            </form>
        </div>
    </div>



    <!-- Tabel Laporan Pemasukan -->
     
    @if(count($pemasukan))
     <a href="{{ route('admin.laporan.export.pdf', request()->all()) }}" target="_blank" class="btn btn-danger mt-3">
    Cetak PDF
</a>
        <h5 class="text-success mt-4">
            Laporan Pemasukan - {{ \Carbon\Carbon::createFromFormat('Y-m', request('bulan'))->translatedFormat('F Y') }}
        </h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Tamu</th>
                        <th>Kamar</th>
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemasukan as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $item->reservasi->nama_tamu ?? '-' }}</td>
                            <td>{{ $item->reservasi->kamar->nomor_kamar ?? '-' }}</td>
                            <td>Rp {{ number_format($item->reservasi->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Tabel Laporan Pengeluaran -->
    @if(count($pengeluaran))
    <a href="{{ route('admin.laporan.export.pdf', request()->all()) }}" target="_blank" class="btn btn-danger mt-3">
    Cetak PDF
</a>
<a href="{{ route('admin.laporan.export.excel', request()->all()) }}" class="btn btn-success mt-3">
    Export Excel
</a>

        <h5 class="text-danger mt-5">Laporan Pengeluaran</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengeluaran as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
