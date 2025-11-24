@extends('layouts.resepsionis')

@section('content')
<style>
    /* Hanya styling form dalam modal */
    .modal .form-control,
    .modal input[type="date"],
    .modal input[type="text"],
    .modal input[type="number"] {
        border: 1px solid #ced4da !important;
        background-color: #fff !important;
        padding: 6px 10px !important;
        border-radius: 6px !important;
        font-style: normal !important; /* cegah italic */
    }

    .modal .form-control:focus {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important;
    }

    /* Pastikan sidebar tidak ikut terpengaruh */
    .navbar-vertical .nav-link,
    .navbar-vertical .nav-link span {
        font-style: normal !important;
    }
</style>

<div class="container">
    <h3>Daftar Reservasi</h3>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalReservasi">
    Tambah Reservasi
</button>


<!-- ===================================================== -->
<!--                     TABEL RESERVASI                   -->
<!-- ===================================================== -->
<div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Tabel Reservasi</h6>
        </div>
    </div>

    <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th>Nama Tamu</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total</th>
                        <th>Extra</th> <!-- Tambahan -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($reservasis as $item)
                    <tr>
                        {{-- tampilkan nomor_kamar jika ada, fallback ke nama_kamar atau '-' --}}
                        <td>{{ $item->kamar->nomor_kamar ?? $item->kamar->nama_kamar ?? '-' }}</td>

                        {{-- nama tamu --}}
                        <td>{{ $item->nama_tamu ?? '-' }}</td>

                        {{-- pastikan gunakan field yang benar di model (cek nama kolom: check_in / tanggal_checkin) --}}
                        <td>{{ $item->check_in ?? $item->tanggal_checkin ?? '-' }}</td>
                        <td>{{ $item->check_out ?? $item->tanggal_checkout ?? '-' }}</td>

                        {{-- total (formatkan jika perlu) --}}
                        <td>{{ $item->total ? 'Rp ' . number_format($item->total,0,',','.') : '-' }}</td>

                        <!-- BAGIAN MAKANAN -->
                        <td>
                            @if ($item->makanans && $item->makanans->count() > 0)
                              
                                    @foreach ($item->makanans as $makanan)
                                        <option>
                                            {{ $makanan->nama }}
                                        </option>
                                    @endforeach
                              
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                       
                        {{-- Aksi: selesai / ubah / hapus --}}
                        <td class="d-flex gap-1">
                            <!-- tombol Selesai (POST ke route yang Anda buat) -->
                            <form action="{{ route('resepsionis.reservasi.selesai', $item->id) }}" method="POST" onsubmit="return confirm('Tandai reservasi sebagai selesai?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                            </form>

                            <!-- tombol Ubah -->

                            <!-- tombol Hapus -->
                            <form action="{{ route('resepsionis.reservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus reservasi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
 
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>




<!-- ===================================================== -->
<!--                     MODAL RESERVASI                   -->
<!-- ===================================================== -->
<div class="modal fade" id="modalReservasi" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">  
        <form method="POST" action="{{ route('resepsionis.reservasi.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Reservasi</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                  <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">

                    <h5>Data Reservasi</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tipe Kamar</label>
                            <select id="tipe_id" class="form-select" required>
                                <option value="">-- Pilih Tipe --</option>
                                @foreach($tipeKamars as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                       <div class="col-md-6 mb-3">
                                <label>Nomor Kamar</label>
                                <select name="kamar_id" id="kamar_id" class="form-select" required>
                                    <option value="">-- Pilih Kamar --</option>

                                    @foreach($kamars as $k)
                                        <!-- { changed code } -->
                                        <option value="{{ $k->id }}"
                                                data-tipe="{{ $k->tipe_id }}"
                                                data-status="{{ $k->status }}" hidden>
                                            Kamar {{ $k->nomor_kamar }}
                                        </option>
                                        <!-- { changed code } -->
                                    @endforeach
                                </select>
                            </div>

                        <div class="mb-3">
                            <label>Nama Tamu</label>
                            <input type="text" name="nama_tamu" class="form-control" required>
                        </div>

                        <div class="text-center my-3">
                            <img id="previewFotoKamar" style="display:none;width:250px;border-radius:10px;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tanggal Check-in</label>
                                <input type="date" name="tanggal_checkin" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal Check-out</label>
                                <input type="date" name="tanggal_checkout" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <strong>Total:</strong> <span id="totalHarga">Rp 0</span>

                    <br><br>

                    <!-- ===================================================== -->
                    <!--                    MAKANAN DALAM MODAL                -->
                    <!-- ===================================================== -->

                    <h5>Pesan Makanan (Opsional)</h5>
                    <hr>

                    <div class="row">
                        <div class="mb-3">
                            <label>Makanan</label>
                             <select id="makananSelect" class="form-select">
                                        <option value="">-- Pilih Makanan --</option>
                                        @foreach($makanans as $m)
                                            <!-- { changed code } -->
                                            <option value="{{ $m->id }}"
                                                data-harga="{{ $m->harga }}"
                                                data-foto="{{ $m->foto ? asset('storage/uploads/makanan/'.$m->foto) : asset('images/no-image.jpg') }}"
                                                data-deskripsi="{{ $m->deskripsi ?? '-' }}">
                                                {{ $m->nama }}
                                            </option>
                                            <!-- { changed code } -->
                                        @endforeach
                                    </select>
                        </div>

                        <div class="col-md-3 mb-3">
                         <h6>List Makanan:</h6>
                    <ul id="listMakanan"></ul>
                    </div>

                    <div id="previewMakanan" class="text-center" style="display:none">
                        <img id="fotoMakanan" style="width:200px;border-radius:10px;">
                        <p id="deskripsiMakanan" class="mt-2"></p>
                        <p><strong id="hargaMakanan"></strong></p>
                    </div>

                    <hr>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elemen utama
    const tipeSelect = document.getElementById('tipe_id');
    const kamarSelect = document.getElementById('kamar_id');
    const previewFoto = document.getElementById('previewFotoKamar');
    const checkinEl = document.querySelector('input[name="tanggal_checkin"]');
    const checkoutEl = document.querySelector('input[name="tanggal_checkout"]');
    const totalEl = document.getElementById('totalHarga');

    // Makanan / preview (modal) + alternatif (index)
    const makananSelectModal = document.getElementById('makananSelect');
    const makananSelectIndex = document.getElementById('dropdownMakanan'); // jika ada
    const listMakanan = document.getElementById('listMakanan');
    const previewMakanan = document.getElementById('previewMakanan');
    const fotoMakanan = document.getElementById('fotoMakanan');
    const deskripsiMakanan = document.getElementById('deskripsiMakanan');
    const hargaMakanan = document.getElementById('hargaMakanan');

    const altPreviewBox = document.getElementById('makananPreview'); // index preview wrapper
    const altFoto = document.getElementById('previewFoto');
    const altNama = document.getElementById('previewNama');
    const altHarga = document.getElementById('previewHarga');

    // Map harga per tipe (dinamis dari server)
    const hargaTipeMap = {
        @foreach ($tipeKamars as $t)
            "{{ $t->id }}": {{ $t->harga }},
        @endforeach
    };

    const formatRupiah = (n) => 'Rp ' + (Number(n) || 0).toLocaleString('id-ID');

    function getTotalMakanan() {
        let total = 0;
        if (!listMakanan) return total;
        listMakanan.querySelectorAll('li').forEach(li => {
            total += Number(li.dataset.harga || 0);
        });
        return total;
    }

    // updateTotal: hanya hitung dan tampilkan setelah tanggal (checkin+checkout) valid
    function updateTotal() {
        const tipeId = tipeSelect?.value;
        const hargaPerMalam = Number(hargaTipeMap[tipeId] || 0);
        const makananTotal = getTotalMakanan();

        const checkin = new Date(checkinEl?.value);
        const checkout = new Date(checkoutEl?.value);

        if (!isNaN(checkin) && !isNaN(checkout) && hargaPerMalam > 0) {
            const selisihHari = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            const malam = selisihHari > 0 ? selisihHari : 0;
            const totalKamar = malam * hargaPerMalam;
            const grand = totalKamar + makananTotal;
            if (totalEl) totalEl.textContent = formatRupiah(grand);
        } else {
            if (totalEl) totalEl.textContent = formatRupiah(0);
        }
    }

    // Filter kamar berdasarkan tipe + status tersedia
    function filterKamarByTipe() {
        if (!kamarSelect) return;
        kamarSelect.value = "";
        Array.from(kamarSelect.options).forEach(o => {
            const tipeMatch = o.dataset.tipe === tipeSelect.value || o.value === "";
            const status = (o.dataset.status || '').toLowerCase();
            const tersedia = status === '' || status === 'tersedia';
            o.hidden = !(tipeMatch && tersedia);
        });
    }

    async function fetchFotoTipe(tipeId) {
        if (!previewFoto) return;
        if (!tipeId) { previewFoto.style.display = "none"; return; }
        try {
            const res = await fetch(`/resepsionis/get-foto-tipe/${tipeId}`);
            const data = await res.json();
            previewFoto.src = data.foto || '{{ asset("images/no-image.jpg") }}';
            previewFoto.style.display = "block";
        } catch (e) {
            previewFoto.style.display = "none";
        }
    }

    // Tampilkan preview makanan (modal + alternatif index)
    function showPreviewMakanan(opt) {
        if (!opt) {
            if (previewMakanan) previewMakanan.style.display = 'none';
            if (altPreviewBox) altPreviewBox.style.display = 'none';
            return;
        }

        const foto = opt.dataset.foto || '{{ asset("images/no-image.jpg") }}';
        const desc = opt.dataset.deskripsi || opt.dataset.nama || '-';
        const harga = Number(opt.dataset.harga) || 0;
        const nama = opt.dataset.nama || (opt.textContent || opt.innerText || '').trim();

        if (fotoMakanan) {
            fotoMakanan.src = foto;
            fotoMakanan.onerror = () => fotoMakanan.src = '{{ asset("images/no-image.jpg") }}';
        }
        if (deskripsiMakanan) deskripsiMakanan.textContent = desc;
        if (hargaMakanan) hargaMakanan.textContent = formatRupiah(harga);
        if (previewMakanan) previewMakanan.style.display = 'block';

        if (altFoto) { altFoto.src = foto; altFoto.onerror = () => altFoto.src = '{{ asset("images/no-image.jpg") }}'; }
        if (altNama) altNama.textContent = nama;
        if (altHarga) altHarga.textContent = 'Rp ' + harga.toLocaleString('id-ID');
        if (altPreviewBox) altPreviewBox.style.display = 'block';
    }

    // Tambah makanan ke list (prevent duplikat) dan update total
    function addMakananFromOption(opt) {
        if (!opt || !listMakanan) return;
        const id = opt.value;
        const nama = opt.dataset.nama || (opt.textContent || opt.innerText || '').trim();
        const harga = Number(opt.dataset.harga) || 0;
        const exists = Array.from(listMakanan.querySelectorAll('li')).some(li => li.dataset.id === id);
        if (exists) return;

        const li = document.createElement('li');
        li.dataset.id = id;
        li.dataset.harga = harga;

        const span = document.createElement('span');
        span.textContent = `${nama} — ${formatRupiah(harga)}`;

        const hidId = document.createElement('input');
        hidId.type = 'hidden'; hidId.name = 'makanan_id[]'; hidId.value = id;

        const hidHarga = document.createElement('input');
        hidHarga.type = 'hidden'; hidHarga.name = 'harga_makanan[]'; hidHarga.value = harga;

        const btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'btn btn-sm btn-danger ms-2 remove-makanan'; btn.textContent = 'Hapus';

        li.appendChild(span);
        li.appendChild(hidId);
        li.appendChild(hidHarga);
        li.appendChild(btn);
        listMakanan.appendChild(li);

        updateTotal();
    }

    // Unified handler for selects (modal + index)
    function handleMakananSelectChange(selectEl) {
        const opt = selectEl.options[selectEl.selectedIndex];
        if (!opt || !selectEl.value) { showPreviewMakanan(null); return; }

        console.log('makanan preview URL:', opt.dataset.foto); // debug
        showPreviewMakanan(opt);
        addMakananFromOption(opt);

        // reset select if desired
        selectEl.value = '';
    }

    // Attach listeners to available selects
    if (makananSelectModal) makananSelectModal.addEventListener('change', function(){ handleMakananSelectChange(this); });
    if (makananSelectIndex) makananSelectIndex.addEventListener('change', function(){ handleMakananSelectChange(this); });

    // Delegated remove handler
    listMakanan?.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-makanan')) {
            e.target.closest('li')?.remove();
            updateTotal();
        }
    });

    // Tipe & tanggal listeners
    tipeSelect?.addEventListener('change', () => {
        filterKamarByTipe();
        fetchFotoTipe(tipeSelect.value);
        // jangan panggil updateTotal di sini — total muncul setelah tanggal diisi
    });
    checkinEl?.addEventListener('change', updateTotal);
    checkoutEl?.addEventListener('change', updateTotal);

    // Init
    filterKamarByTipe();
    if (tipeSelect?.value) fetchFotoTipe(tipeSelect.value);
    // jangan auto update total di init supaya tidak menghitung sebelum tanggal diisi
});
</script>

@endsection
