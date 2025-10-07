@extends('layouts.admin')

@section('title', 'Riwayat Pengeluaran')

@section('content')
<div class="page-heading mb-4">
    <h3>Riwayat Pengeluaran</h3>
</div>

<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Riwayat</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Pesan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluarans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>
                                telah menambahkan pengeluaran {{ $p->nama_barang }} {{ $p->jumlah_barang }} pcs 
                                dengan biaya Rp. {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td>{{ $p->created_at->format('d-m-Y, H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table1').DataTable({
            language: {
                "lengthMenu": "Menampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                },
            }
        });
    });
</script>
@endpush
