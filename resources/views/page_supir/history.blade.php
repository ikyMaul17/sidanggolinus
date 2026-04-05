@extends('layouts_supir.app')

@section('css')
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center">
        <h5>History Perjalanan</h5>
    </div>

    <!-- Daftar Penumpang -->
    <div class="mt-3">
        <table class="table table-striped" style="font-size: 14px;">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Berangkat</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $data)
                <tr>
                    <td>{{ $data->kode }}</td>
                    <td>{{ $data->nama_penumpang }}</td>
                    <td>{{ $data->halte_penjemputan }}</td>
                    <td>{{ $data->halte_tujuan }}</td>
                    <td><span class="badge bg-warning">Selesai</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('script')
@endsection
