@extends('layouts.resepsionis')

@section('title', 'Dashboard Resepsionis')

@section('content')
<div class="page-heading mb-4">
    <h3>Dashboard Resepsionis</h3>
    <p class="text-subtitle text-muted">Ringkasan aktivitas hari ini di hotel.</p>
</div>

<div class="row g-4">

    <!-- Reservasi Hari Ini -->
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100" style="background: #e8f4ff;">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="material-symbols-rounded fs-1 text-primary">event_available</span>
                </div>
                <div>
                    <h6 class="card-title mb-1">Reservasi Hari Ini</h6>
                    <h3 class="fw-bold">{{ $reservasiHariIni }}</h3>
                    <p class="text-muted mb-0">Tamu yang melakukan reservasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Tersedia -->
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100" style="background: #e9ffe8;">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="material-symbols-rounded fs-1 text-success">bed</span>
                </div>
                <div>
                    <h6 class="card-title mb-1">Kamar Tersedia</h6>
                    <h3 class="fw-bold">{{ $kamarTersedia }}</h3>
                    <p class="text-muted mb-0">Siap untuk dipesan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Terisi -->
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 h-100" style="background: #fff4e6;">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="material-symbols-rounded fs-1 text-warning">hotel</span>
                </div>
                <div>
                    <h6 class="card-title mb-1">Kamar Terisi</h6>
                    <h3 class="fw-bold">{{ $kamarTerisi }}</h3>
                    <p class="text-muted mb-0">Saat ini ditempati</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
