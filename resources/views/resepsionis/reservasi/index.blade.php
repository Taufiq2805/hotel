    @extends('layouts.resepsionis')

    @section('content')
    <div class="container">
        <h3>Daftar Reservasi</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalReservasi">
            Tambah Reservasi
        </button>

        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Tabel Reservasi</h6>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kamar</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Check-In</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Check-Out</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasis as $r)
                                        <tr>
                                            <td>{{ $r->kamar->nomor_kamar ?? 'Kamar tidak ditemukan' }}</td>
                                            <td>{{ $r->tanggal_checkin }}</td>
                                            <td>{{ $r->tanggal_checkout }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match($r->status) {
                                                        'terisi' => 'bg-success',
                                                        'tersedia' => 'bg-primary',
                                                        'dibersihkan' => 'bg-warning',
                                                        'maintenance' => 'bg-danger',
                                                        'selesai' => 'bg-secondary',
                                                        default => 'bg-dark'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($r->status) }}</span>
                                            </td>
                                            <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                                        <td>
        <a href="{{ route('resepsionis.reservasi.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>
        
        <form action="{{ route('resepsionis.reservasi.destroy', $r->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin hapus reservasi ini?')" class="btn btn-sm btn-danger">Hapus</button>
        </form>

    <form action="{{ route('resepsionis.reservasi.selesai', $r->id) }}" method="POST" style="display:inline-block;">
        @csrf
        <button onclick="return confirm('Tandai sebagai selesai?')" class="btn btn-sm btn-success">Selesai</button>
    </form>

    </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Modal Tambah Reservasi -->
        <div class="modal fade" id="modalReservasi" tabindex="-1" aria-labelledby="modalReservasiLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('resepsionis.reservasi.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalReservasiLabel">Tambah Reservasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <!-- Tipe Kamar -->
                                <div class="col-md-6 mb-3">
                                    <label for="tipe_id" class="form-label">Tipe Kamar</label>
                                    <select id="tipe_id" class="form-select" required>
                                        <option value="">-- Pilih Tipe Kamar --</option>
                                        @foreach($tipeKamars as $tipe)
                                            <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nomor Kamar -->
                                <div class="col-md-6 mb-3">
                                    <label for="kamar_id" class="form-label">Nomor Kamar</label>
                                    <select name="kamar_id" id="kamar_id" class="form-select" required>
                                        <option value="">-- Pilih Nomor Kamar --</option>
                                        @foreach($kamars as $kamar)
                                            <option value="{{ $kamar->id }}" data-tipe="{{ $kamar->tipe_id }}" hidden>
                                                Kamar {{ $kamar->nomor_kamar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nama Tamu -->
                                <div class="mb-3">
                                    <label for="nama_tamu" class="form-label">Nama Tamu</label>
                                    <input type="text" name="nama_tamu" class="form-control border border-secondary" required>
                                </div>

                                <!-- Foto Tipe Kamar -->
                                <div class="text-center my-3">
    <img id="previewFotoKamar" src="" alt="Foto Kamar" style="display:none; width: 250px; border-radius: 10px; margin-top: 10px;">

    </div>
                                <!-- Tanggal -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_checkin" class="form-label">Tanggal Check-in</label>
                                        <input type="date" name="tanggal_checkin" class="form-control border border-secondary" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_checkout" class="form-label">Tanggal Check-out</label>
                                        <input type="date" name="tanggal_checkout" class="form-control border border-secondary" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <strong>Total:</strong> <span id="totalHarga">Rp 0</span>
                            </div>
                        </div>
                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- SCRIPT PILIH TIPE KAMAR & HITUNG TOTAL -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipeSelect = document.getElementById('tipe_id');
        const kamarSelect = document.getElementById('kamar_id');
        const checkinInput = document.querySelector('input[name="tanggal_checkin"]');
        const checkoutInput = document.querySelector('input[name="tanggal_checkout"]');
        const totalDisplay = document.getElementById('totalHarga');
        const previewFoto = document.getElementById('previewFotoKamar');

        const hargaKamarMap = {
            @foreach ($kamars as $kamar)
                "{{ $kamar->id }}": {{ $kamar->tipe->harga ?? 0 }},
            @endforeach
        };

        function filterKamar() {
            const selectedTipe = tipeSelect.value;
            kamarSelect.value = "";

            Array.from(kamarSelect.options).forEach(option => {
                const tipeId = option.getAttribute('data-tipe');
                option.hidden = tipeId !== selectedTipe && option.value !== "";
            });

            totalDisplay.textContent = 'Rp 0';

            // 🔥 Ambil foto dari controller
        if (selectedTipe) {
        fetch(`{{ url('/resepsionis/get-foto-tipe') }}/${selectedTipe}`)
            .then(response => response.json())
            .then(data => {
                if (data.foto) {
                    previewFoto.src = data.foto;
                    previewFoto.style.display = 'block';
                } else {
                    previewFoto.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error ambil foto:', error);
                previewFoto.style.display = 'none';
            });
    } else {
        previewFoto.style.display = 'none';
    }

        }

        function hitungTotal() {
            const kamarId = kamarSelect.value;
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);
            const hargaPerMalam = hargaKamarMap[kamarId] || 0;

            if (!isNaN(checkin) && !isNaN(checkout) && hargaPerMalam > 0) {
                const selisihHari = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
                const total = selisihHari > 0 ? selisihHari * hargaPerMalam : 0;
                totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                totalDisplay.textContent = 'Rp 0';
            }
        }

        tipeSelect.addEventListener('change', filterKamar);
        kamarSelect.addEventListener('change', hitungTotal);
        checkinInput.addEventListener('change', hitungTotal);
        checkoutInput.addEventListener('change', hitungTotal);
    });
    </script>
    @endsection
