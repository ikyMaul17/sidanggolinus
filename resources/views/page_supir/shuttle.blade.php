@extends('layouts_supir.app')

@section('css')
@endsection

@section('content')
<section class="container py-3">
    <!-- Baris Penumpang dan Kapasitas -->
    <div class="d-flex justify-content-between">
        <span>Penumpang: {{ $count_penumpang }}</span>
        <span>Kapasitas: {{ $bus->kapasitas }}</span>
    </div>

    <!-- Daftar Penumpang -->
    <div class="mt-3">
        <table class="table table-striped" style="font-size: 15px;">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Berangkat</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penumpang as $data)
                <tr>
                    <td>{{ $data->penumpang->nama }}</td>
                    <td>{{ $data->nama_penjemputan }}</td>
                    <td>{{ $data->nama_tujuan }}</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        

    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
