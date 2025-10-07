@extends('layouts.housekeeping')

@section('title', 'Data Pengeluaran')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Manajemen Pengeluaran</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Pengeluaran</button>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Keterangan</th>
                    <th>Tanggal Pengeluaran</th>
                    <th>Jumlah Pengeluaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluarans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ $p->nama_barang }} {{ $p->jumlah_barang }} pcs</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pengeluaran)->format('d-m-Y') }}</td>
                    <td>Rp. {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $p->id }}">Ubah</button>

                        <!-- Form Hapus -->
                        <form action="{{ route('housekeeping.pengeluaran.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit-{{ $p->id }}" tabindex="-1" aria-labelledby="modalEditLabel-{{ $p->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('housekeeping.pengeluaran.update', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pengeluaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Pengeluaran</label>
                                        <input type="date" name="tanggal_pengeluaran" class="form-control" value="{{ $p->tanggal_pengeluaran }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" name="nama_barang" class="form-control" value="{{ $p->nama_barang }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah Barang</label>
                                        <input type="number" name="jumlah_barang" class="form-control jumlah" value="{{ $p->jumlah_barang }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Harga Satuan</label>
                                        <input type="number" name="harga_satuan" class="form-control harga" value="{{ $p->harga_satuan }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Harga</label>
                                        <input type="number" name="total_harga" class="form-control total" value="{{ $p->total_harga }}" readonly>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-success">Simpan Perubahan</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('housekeeping.pengeluaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal_pengeluaran" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jumlah Barang</label>
                        <input type="number" name="jumlah_barang" class="form-control jumlah" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Satuan</label>
                        <input type="number" name="harga_satuan" class="form-control harga" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Harga</label>
                        <input type="number" name="total_harga" class="form-control total" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setupAutoTotal(modalElement) {
        const jumlahInput = modalElement.querySelector('input[name="jumlah_barang"]');
        const hargaInput = modalElement.querySelector('input[name="harga_satuan"]');
        const totalInput = modalElement.querySelector('input[name="total_harga"]');

        if (jumlahInput && hargaInput && totalInput) {
            function updateTotal() {
                const jumlah = parseInt(jumlahInput.value) || 0;
                const harga = parseInt(hargaInput.value) || 0;
                totalInput.value = jumlah * harga;
            }

            jumlahInput.addEventListener('input', updateTotal);
            hargaInput.addEventListener('input', updateTotal);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Modal Tambah
        const modalTambah = document.getElementById('modalTambah');
        if (modalTambah) {
            modalTambah.addEventListener('shown.bs.modal', function () {
                setupAutoTotal(modalTambah);
            });
        }

        // Modal Edit
        const editModals = document.querySelectorAll('[id^="modalEdit-"]');
        editModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                setupAutoTotal(modal);
            });
        });
    });
</script>
@endpush

