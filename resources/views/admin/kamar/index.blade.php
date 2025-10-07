@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <form action="{{ route('admin.kamar.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label for="tipe_id" class="form-label">Filter berdasarkan Tipe Kamar</label>
            <select name="tipe_id" id="tipe_id" class="form-select">
                <option value="">-- Semua Tipe --</option>
                @foreach ($tipeKamar as $tipe)
                    <option value="{{ $tipe->id }}" {{ request('tipe_id') == $tipe->id ? 'selected' : '' }}>
                        {{ $tipe->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
        <div class="col-md-auto">
            <a href="{{ route('admin.kamar.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Kamar</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Kamar
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kamar as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nomor_kamar }}</td>
                            <td>{{ $item->tipe->nama }}</td>
                            <td>
                                @php
                                    $badgeClass = match($item->status) {
                                        'tersedia' => 'bg-success',
                                        'terisi' => 'bg-primary',
                                        'dibersihkan' => 'bg-warning',
                                        'maintenance' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.kamar.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Kamar</label>
                        <select name="tipe_id" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            @foreach ($tipeKamar as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                            <option value="dibersihkan">Dibersihkan</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable(document.querySelector('#table1'));
    });
</script>
@endpush
