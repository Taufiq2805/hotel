@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Tipe Kamar</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTipe">
                + Tambah Tipe Kamar
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tableTipeKamar">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tipeKamar as $index => $tipe)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tipe->nama }}</td>
                            <td>Rp {{ number_format($tipe->harga, 0, ',', '.') }}</td>
                            <td>{{Str::limit($tipe->deskripsi, 16)}}</td>
                            <td>
                                @if($tipe->foto)
                                    <img src="{{ asset('uploads/tipekamar/' . $tipe->foto) }}" width="80" height="50" style="object-fit: cover;">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTipe{{ $tipe->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.tipekamar.destroy', $tipe->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus tipe kamar ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="modalEditTipe{{ $tipe->id }}" tabindex="-1" aria-labelledby="modalEditTipeLabel{{ $tipe->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <form action="{{ route('admin.tipekamar.update', $tipe->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                  <h5 class="modal-title">Edit Tipe Kamar</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                      <label class="form-label">Nama</label>
                                      <input type="text" name="nama" class="form-control" value="{{ $tipe->nama }}" required>
                                  </div>
                                  <div class="mb-3">
                                      <label class="form-label">Harga</label>
                                      <input type="number" name="harga" class="form-control" value="{{ $tipe->harga }}" required>
                                  </div>
                                  <div class="mb-3">
                                      <label class="form-label">Deskripsi</label>
                                      <textarea name="deskripsi" class="form-control" rows="3">{{ $tipe->deskripsi }}</textarea>
                                  </div>
                                  <div class="mb-3">
                                      <label class="form-label">Foto Baru (jika ingin ganti)</label>
                                      <input type="file" name="foto" class="form-control">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahTipe" tabindex="-1" aria-labelledby="modalTambahTipeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.tipekamar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tipe Kamar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Harga</label>
              <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new simpleDatatables.DataTable(document.getElementById('tableTipeKamar'));
    });
</script>
@endpush
