@extends('layouts_supir.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Perbarui Profil</h5>
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/user-profile-illustration-download-in-svg-png-gif-file-formats--id-login-register-technology-pack-network-communication-illustrations-2928727.png" alt="Login Banner" class="img-fluid" style="max-width: 50%; border-radius: 10px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('update_profile_supir') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', auth()->user()->nama) }}" required>
                    @error('nama')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jk">Jenis Kelamin</label>
                    <select name="jk" id="jk" class="form-control @error('jk') is-invalid @enderror" required>
                        <option value="Laki-laki" {{ old('jk', auth()->user()->jk) == 'Laki-laki' || is_null(auth()->user()->jk) ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jk', auth()->user()->jk) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jk')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Nomor WhatsApp -->
                <div class="mb-3">
                    <label for="no_wa">Nomor WhatsApp</label>
                    <input type="text" name="no_wa" id="no_wa" class="form-control @error('no_wa') is-invalid @enderror" value="{{ old('no_wa', auth()->user()->no_wa) }}" required>
                    @error('no_wa')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah kata sandi.</small>
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Avatar -->
                <div class="mb-3">
                    <label for="avatar">Foto</label>
                    <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    <small class="form-text text-muted">Unggah foto Anda.</small>
                    @error('avatar')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Preview Avatar -->
                <div class="mb-3 text-center">
                    @php
                        $defaultAvatar = 'https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png';

                        $avatar = auth()->user()->avatar 
                            ? asset('storage/' . auth()->user()->avatar) 
                            : (auth()->user()->jk == 'Perempuan' 
                                ? 'https://cdn-icons-png.flaticon.com/512/219/219969.png' 
                                : (auth()->user()->jk == 'Laki-laki' 
                                    ? 'https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png' 
                                    : $defaultAvatar));
                    @endphp

                    <img id="avatar-preview" src="{{ $avatar }}" alt="Avatar Preview" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                </div>

                <!-- Tombol Update -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    document.getElementById('avatar').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
    @endif
</script>
@endsection
