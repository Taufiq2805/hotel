@extends('layouts.housekeeping')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Data Maintenance Kamar</h4>

            <!-- Tombol Tambah Maintenance -->
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
                            <td class="editable-catatan" data-id="{{ $m->id }}">
                                {{ $m->catatan ?? '-' }}
                            </td>
                            <td>{{ $m->user->name ?? '-' }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning btnEdit" 
                                    data-id="{{ $m->id }}"
                                    data-kamar="{{ $m->kamar_id }}"
                                    data-tanggal="{{ $m->tanggal }}"
                                    data-status="{{ $m->status }}"
                                    data-catatan="{{ $m->catatan }}">
                                    <i class="ti ti-edit"></i>
                                </button>

                                <!-- Tombol Selesai -->
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('housekeeping.maintenance.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Maintenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Kamar</label>
                        <select name="kamar_id" class="form-select" required>
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($kamars as $k)
                                <option value="{{ $k->id }}">{{ $k->nomor_kamar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="maintenance">Maintenance</option>
                            <option value="dibersihkan">Dibersihkan</option>
                            <option value="terisi">Terisi</option>
                            <option value="tersedia">Tersedia</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" placeholder="Tulis kondisi kamar, misal: AC bocor, kipas rusak, dll"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Global -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Maintenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kamar</label>
                        <select name="kamar_id" id="editKamar" class="form-select" required>
                            @foreach($kamars as $k)
                                <option value="{{ $k->id }}">{{ $k->nomor_kamar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" id="editTanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="editStatus" class="form-select" required>
                            @foreach(['tersedia', 'terisi', 'dibersihkan', 'maintenance'] as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea name="catatan" id="editCatatan" class="form-control" placeholder="Misal: Kipas rusak, Lampu mati, dll"></textarea>
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

<script>
document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('editKamar').value = this.dataset.kamar;
        document.getElementById('editTanggal').value = this.dataset.tanggal;
        document.getElementById('editStatus').value = this.dataset.status;
        document.getElementById('editCatatan').value = this.dataset.catatan ?? '';

        const form = document.getElementById('formEdit');
        form.action = `/housekeeping/maintenance/${id}`;
        const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
        modal.show();
    });
});

// Inline edit catatan (double click)
document.querySelectorAll('.editable-catatan').forEach(cell => {
    cell.addEventListener('dblclick', function() {
        const id = this.dataset.id;
        const oldText = this.innerText;
        const input = document.createElement('input');
        input.value = oldText;
        input.className = 'form-control';
        this.innerHTML = '';
        this.appendChild(input);
        input.focus();

        input.addEventListener('blur', () => {
            const newText = input.value;
            this.innerText = newText;
            fetch('/housekeeping/maintenance/update-catatan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, catatan: newText })
            });
        });
    });
});
</script>
@endsection
