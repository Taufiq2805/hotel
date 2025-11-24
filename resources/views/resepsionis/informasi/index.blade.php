@extends('layouts.resepsionis')

@section('title', 'Informasi Hotel')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Informasi Hotel</h4>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="120">Foto</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($informasi as $item)
                        <tr>
                            <td>
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         width="100" height="70"
                                         style="object-fit: cover; border-radius: 6px;">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>

                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->deskripsi }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data informasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
