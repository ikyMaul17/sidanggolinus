@extends('layouts_supir.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Pengaturan Bus</h5>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('update_setting_bus') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama">Nama Bus</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $bus->nama }}" required>
                </div>

                <div class="mb-3">
                    <label for="nama">Kapasitas</label>
                    <input type="number" name="kapasitas" id="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ $bus->kapasitas }}" required>
                </div>


                <!-- Tombol Update -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
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
