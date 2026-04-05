@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Reset Kata Sandi</h5>
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/user-profile-illustration-download-in-svg-png-gif-file-formats--id-login-register-technology-pack-network-communication-illustrations-2928727.png" alt="Login Banner" class="img-fluid" style="max-width: 50%; border-radius: 10px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="text-center">Permintaan untuk reset kata sandi akun Anda akan segera dikirim melalui email</p>
           <form action="{{ route('reset_password') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="" required>
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
