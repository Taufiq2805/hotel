@extends('layouts.resepsionis')

@section('content')
<div class="page-heading mb-4">
    <h3>Dashboard Resepsionis</h3>
    <p class="text-subtitle text-muted">Ringkasan aktivitas hari ini di hotel.</p>
</div>

<div class="row">
    <!-- Reservasi Hari Ini -->
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Reservasi Hari Ini</h6>
                <h3>{{ $reservasiHariIni }}</h3>
                <p class="text-muted mb-0">Tamu yang melakukan reservasi</p>
            </div>
        </div>
    </div>

    <!-- Kamar Tersedia -->
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Kamar Tersedia</h6>
                <h3>{{ $kamarTersedia }}</h3>
                <p class="text-muted mb-0">Siap untuk dipesan</p>
            </div>
        </div>
    </div>

    <!-- Kamar Terisi -->
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Kamar Terisi</h6>
                <h3>{{ $kamarTerisi }}</h3>
                <p class="text-muted mb-0">Saat ini ditempati</p>
            </div>
        </div>
    </div>

    <!-- Check-In Hari Ini -->
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Check-In Hari Ini</h6>
                <h3>{{ $jumlahCheckin }}</h3>
                <p class="text-muted mb-0">Tamu yang sudah check-in</p>
            </div>
        </div>
    </div>
</div>
@endsection
