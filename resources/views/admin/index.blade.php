@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-heading mb-4">
    <h3>Dashboard Admin</h3>
    <p class="text-subtitle text-muted">Ringkasan data hotel secara cepat.</p>
</div>

<div class="row">
    <!-- Total Tipe Kamar -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Tipe Kamar</h5>
                <h2>{{ $totalTipeKamar }}</h2>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.tipekamar.index') }}" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Kamar -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Total Kamar</h5>
                <h2>{{ $totalKamar }}</h2>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.kamar.index') }}" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Reservasi -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Total Reservasi</h5>
                <h2>{{ $totalReservasi }}</h2>
            </div>
            <div class="card-footer">
                <a href="{{ route('resepsionis.reservasi.index') }}" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Check-in -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <h5 class="card-title">Check-in Saat Ini</h5>
                <h2>{{ $totalCheckin }}</h2>
            </div>
            <div class="card-footer">
                <a href="{{ route('resepsionis.reservasi.index') }}" class="text-white text-decoration-none">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Sisa Uang -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <h5 class="card-title">Sisa Uang</h5>
                <h3>Rp {{ number_format($sisaUang, 0, ',', '.') }}</h3>
            </div>
            <div class="card-footer">
                <span class="text-white">Total pemasukan</span>
            </div>
        </div>
    </div>
</div>

<!-- Shortcut Menu -->
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <a href="{{ route('admin.tipekamar.index') }}" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-bed fa-2x mb-2"></i>
                    <h5>Tipe Kamar</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('admin.kamar.index') }}" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-door-closed fa-2x mb-2"></i>
                    <h5>Kamar</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('resepsionis.reservasi.index') }}" class="text-decoration-none">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h5>Reservasi</h5>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
