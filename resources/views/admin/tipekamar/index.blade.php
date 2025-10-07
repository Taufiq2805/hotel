@extends('layouts.admin')

@section('content')

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Kamar</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTipe">
                + Tambah Tipe Kamar
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                          <th>No</th>
                        <th>Nama Kamar</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
             <tbody>
                    @foreach ($tipeKamar as $index => $tipe)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tipe->nama }}</td>
                            <td>Rp {{ number_format($tipe->harga, 0, ',', '.') }}</td>
                            <td>{{ $tipe->deskripsi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>


<!-- Modal Tambah Tipe -->
<div class="modal fade" id="modalTambahTipe" tabindex="-1" aria-labelledby="modalTambahTipeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.tipekamar.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahTipeLabel">Tambah Tipe Kamar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Nama Kamar</label>
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
