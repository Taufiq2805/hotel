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

                        @php
                            $res = $r->reservasi;

                            // Harga kamar + paket
                            $hargaMalam = ($res->kamar->tipe->harga ?? 0) + ($res->paket->harga ?? $res->paket_harga ?? 0);

                            // Hitung durasi
                            $in = \Carbon\Carbon::parse($res->tanggal_checkin);
                            $out = \Carbon\Carbon::parse($res->tanggal_checkout);
                            $lama = max($in->diffInDays($out), 1);

                            $roomTotal = $lama * $hargaMalam;

                            // Hitung makanan
                            $makananTotal = $res->makanans->sum(function($m){
                                return ($m->pivot->harga ?? $m->harga ?? 0) * ($m->pivot->qty ?? 1);
                            });

                            // Jika report sudah punya total → pakai itu
                            $total = $r->total ?? ($roomTotal + $makananTotal);
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $res->nama_tamu }}</td>
                            <td>{{ $res->kamar->nomor_kamar }}</td>
                            <td>{{ $res->tanggal_checkin }}</td>
                            <td>{{ $res->tanggal_checkout }}</td>
                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $r->id }}">
                                    <i class="ti ti-list">Detail</i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modalDetail-{{ $r->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Riwayat Sewa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <p><strong>Nama Tamu:</strong> {{ $res->nama_tamu }}</p>
                                        <p><strong>Nomor Kamar:</strong> {{ $res->kamar->nomor_kamar }}</p>
                                        <p><strong>Tipe Kamar:</strong> {{ $res->kamar->tipe->nama }}</p>
                                        <p><strong>Harga per Malam:</strong> Rp {{ number_format($hargaMalam,0,',','.') }}</p>
                                        <p><strong>Lama Menginap:</strong> {{ $lama }} malam</p>

                                        @if($res->makanans->count() > 0)
                                            <hr>
                                            <strong>Makanan:</strong>
                                            <ul>
                                                @foreach($res->makanans as $m)
                                                    <li>
                                                        {{ $m->nama }} — {{ $m->pivot->qty ?? 1 }}x 
                                                        Rp {{ number_format($m->pivot->harga ?? $m->harga, 0, ',', '.') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        <hr>
                                        <p><strong>Subtotal Kamar:</strong> Rp {{ number_format($roomTotal,0,',','.') }}</p>
                                        <p><strong>Subtotal Makanan:</strong> Rp {{ number_format($makananTotal,0,',','.') }}</p>
                                        <p><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
                                        <p><strong>Catatan:</strong> {{ $r->catatan ?? '-' }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada riwayat sewa</td></tr>
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
