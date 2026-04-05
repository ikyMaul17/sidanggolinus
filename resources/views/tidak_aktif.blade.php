@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Perhatian !</h5>
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/logout-account-illustration-download-in-svg-png-gif-file-formats--exit-here-login-log-out-seo-website-development-pack-web-illustrations-4707120.png" alt="Login Banner" class="img-fluid" style="max-width: 50%; border-radius: 10px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="text-center">Maaf, akun anda sudah tidak aktif</p>
            <a href="{{ url('/') }}"class="btn btn-primary w-100">Kembali ke Home</a>
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
