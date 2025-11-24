@extends('layouts.housekeeping')

@section('title', 'Dashboard Housekeeping')

@section('content')
<style>
    .info-card {
        border-radius: 16px;
        transition: 0.2s;
        border: none;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .icon-blue { background: #4C8CFF; }
    .icon-green { background: #2ECC71; }
    .icon-orange { background: #F39C12; }
    .icon-red { background: #E74C3C; }
    .icon-purple { background: #9B59B6; }

    .card-title {
        font-size: 15px;
        color: #555;
    }

    .card-value {
        font-size: 32px;
        font-weight: 700;
        margin-top: 5px;
    }

</style>

<div class="page-heading mb-4">
    <h3 class="fw-bold">Dashboard Housekeeping</h3>
    <p class="text-muted">Ringkasan kondisi kamar & aktivitas housekeeping.</p>
</div>

<div class="row g-4">

    <!-- Total Kamar -->
    <div class="col-md-3">
        <div class="card info-card shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box icon-blue me-3">
                    <i class="ti ti-home"></i>
                </div>
                <div>
                    <div class="card-title">Total Kamar</div>
                    <div class="card-value">{{ $totalKamar }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Tersedia -->
    <div class="col-md-3">
        <div class="card info-card shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box icon-green me-3">
                    <i class="ti ti-circle-check"></i>
                </div>
                <div>
                    <div class="card-title">Kamar Tersedia</div>
                    <div class="card-value text-success">{{ $kamarTersedia }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kamar Maintenance -->
    <div class="col-md-3">
        <div class="card info-card shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box icon-orange me-3">
                    <i class="ti ti-tools"></i>
                </div>
                <div>
                    <div class="card-title">Kamar Maintenance</div>
                    <div class="card-value text-warning">{{ $kamarMaintenance }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Maintenance -->
    <div class="col-md-3">
        <div class="card info-card shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box icon-red me-3">
                    <i class="ti ti-settings"></i>
                </div>
                <div>
                    <div class="card-title">Total Maintenance</div>
                    <div class="card-value text-danger">{{ $totalMaintenance }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Pengeluaran -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card info-card shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box icon-purple me-3">
                    <i class="ti ti-cash"></i>
                </div>
                <div>
                    <div class="card-title">Pengeluaran Bulan Ini</div>
                    <div class="card-value text-primary">
                        Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
