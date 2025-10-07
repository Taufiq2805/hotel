@extends('layouts.housekeeping')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Data Maintenance Kamar</h4>

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="ti ti-plus"></i> Tambah Maintenance
            </button>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Kamar</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $index => $m)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $m->kamar->nomor_kamar ?? '-' }}</td>
                            <td>{{ $m->tanggal }}</td>
                            <td>
                                <span class="badge 
                                    @if($m->status == 'maintenance') bg-danger 
                                    @elseif($m->status == 'dibersihkan') bg-warning text-dark
                                    @elseif($m->status == 'tersedia') bg-success 
                                    @else bg-secondary 
                                    @endif">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td>{{ $m->catatan ?? '-' }}</td>
                            <td>{{ $m->user->name ?? '-' }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $m->id }}">
                                    <i class="ti ti-edit"></i>
                                </button>

                                <!-- Tombol Selesai (ubah status ke tersedia) -->
                                @if($m->status !== 'tersedia')
                                <form action="{{ route('housekeeping.maintenance.updateStatus', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Selesaikan maintenance dan ubah status kamar ke tersedia?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Selesai">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- Tombol Hapus -->
                                <form action="{{ route('housekeeping.maintenance.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit-{{ $m->id }}" tabindex="-1" aria-labelledby="modalEditLabel-{{ $m->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('housekeeping.maintenance.update', $m->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditLabel-{{ $m->id }}">Edit Maintenance</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="kamar_id" class="form-label">Kamar</label>
                                                <select name="kamar_id" class="form-select" required>
                                                    @foreach($kamars as $k)
                                                        <option value="{{ $k->id }}" {{ $k->id == $m->kamar_id ? 'selected' : '' }}>
                                                            {{ $k->nomor_kamar }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" name="tanggal" class="form-control" value="{{ $m->tanggal }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    @foreach(['tersedia', 'terisi', 'dibersihkan', 'maintenance'] as $status)
                                                        <option value="{{ $status }}" {{ $status == $m->status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="catatan" class="form-label">Catatan</label>
                                                <textarea name="catatan" class="form-control">{{ $m->catatan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-success" type="submit">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data maintenance.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
