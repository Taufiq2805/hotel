@extends('layouts.admin')

@section('title', 'Riwayat Sewa')

@section('content')
<div class="page-heading mb-4">
    <h3>Riwayat Sewa Tamu</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Riwayat</h5>
        </div>
        <div class="card-body">

            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tamu</th>
                        <th>Kamar</th>
                        <th>Tanggal Check-in</th>
                        <th>Tanggal Check-out</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $i => $r)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $r->reservasi->nama_tamu ?? '-' }}</td>
                            <td>{{ $r->reservasi->kamar->nomor_kamar ?? '-' }}</td>
                            <td>{{ $r->reservasi->tanggal_checkin ?? '-' }}</td>
                            <td>{{ $r->reservasi->tanggal_checkout ?? '-' }}</td>
                            <td>
                                @php
                                    $tipe = $r->reservasi->kamar->tipe ?? null;
                                    $hargaPerMalam = $tipe?->harga ?? 0;

                                    $checkin = \Carbon\Carbon::parse($r->reservasi->tanggal_checkin ?? now());
                                    $checkout = \Carbon\Carbon::parse($r->reservasi->tanggal_checkout ?? now());
                                    $lamaMenginap = $checkin->diffInDays($checkout);

                                    $total = $hargaPerMalam * $lamaMenginap;
                                @endphp
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>
                            <td>
                                <!-- Tombol Detail -->
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $r->id }}">
                                    <i class="ti ti-list">Detail</i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="modalDetail-{{ $r->id }}" tabindex="-1" aria-labelledby="modalDetailLabel-{{ $r->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDetailLabel-{{ $r->id }}">Detail Riwayat Sewa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Tamu:</strong> {{ $r->reservasi->nama_tamu ?? '-' }}</p>
                                        <p><strong>Nomor Kamar:</strong> {{ $r->reservasi->kamar->nomor_kamar ?? '-' }}</p>
                                        <p><strong>Tipe Kamar:</strong> {{ $r->reservasi->kamar->tipe->nama ?? '-' }}</p>
                                        <p><strong>Harga / Malam:</strong> Rp {{ number_format($hargaPerMalam, 0, ',', '.') }}</p>
                                        <p><strong>Tanggal Check-in:</strong> {{ $r->reservasi->tanggal_checkin ?? '-' }}</p>
                                        <p><strong>Tanggal Check-out:</strong> {{ $r->reservasi->tanggal_checkout ?? '-' }}</p>
                                        <p><strong>Lama Menginap:</strong> {{ $lamaMenginap }} malam</p>
                                        <p><strong>Total Harga:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
                                        <p><strong>Catatan:</strong> {{ $r->catatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada riwayat sewa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable(document.querySelector('#table1'));
    });
</script>
@endpush
