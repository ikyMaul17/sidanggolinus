@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
<section class="container py-3">

    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="text-center">Silahkan ubah pembaruan kata sandi baru</p>
           <form action="{{ route('store_reset_password') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="" required>
                </div>

                <div class="mb-3">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <!-- Tombol Update -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Kirim</button>
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
