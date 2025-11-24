@extends('layouts.admin')

@section('title', 'Data Shift')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Shift</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahShift">
                + Tambah Shift
            </button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped" id="tableShift">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Shift</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $index => $shift)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $shift->nama_shift }}</td>
                            <td>{{ $shift->jam_mulai }}</td>
                            <td>{{ $shift->jam_selesai }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditShift{{ $shift->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.shift.destroy', $shift->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus shift ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEditShift{{ $shift->id }}" tabindex="-1" aria-labelledby="modalEditShiftLabel{{ $shift->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.shift.update', $shift->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Shift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Shift</label>
                                                <input type="text" name="nama_shift" class="form-control" value="{{ $shift->nama_shift }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jam Mulai</label>
                                                <input type="time" name="jam_mulai" class="form-control" value="{{ $shift->jam_mulai }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jam Selesai</label>
                                                <input type="time" name="jam_selesai" class="form-control" value="{{ $shift->jam_selesai }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahShift" tabindex="-1" aria-labelledby="modalTambahShiftLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.shift.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Shift Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" name="nama_shift" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
