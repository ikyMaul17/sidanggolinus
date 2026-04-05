@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="container py-3">
    <div class="text-center mb-3">
        <h5>Daftar</h5>
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/user-profile-illustration-download-in-svg-png-gif-file-formats--id-login-register-technology-pack-network-communication-illustrations-2928727.png" alt="Login Banner" class="img-fluid" style="max-width: 50%; border-radius: 10px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="password-form" action="{{ route('register_penumpang_store') }}" method="POST">
                @csrf

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control"  required>
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-3">
                    <label for="no_wa">Nomor WhatsApp</label>
                    <input type="text" name="no_wa" id="no_wa" class="form-control"  required>
                </div>

                <!-- NIM -->
                <div class="mb-3">
                    <label for="nim">NIM</label>
                    <input type="text" name="nim" id="nim" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="addFakultas" class="form-label">Fakultas</label>
                    <select name="id_fakultas" id="fakultas-select" class="form-control" required>
                        <option value="">Pilih Fakultas</option>
                        @foreach($fakultas as $f)
                        <option value="{{ $f->id }}">{{ $f->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="addJurusan" class="form-label">Jurusan</label>
                    <select name="id_jurusan" id="jurusan-select" class="form-control" required>
                        <option value="">Pilih Jurusan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="addJk" class="form-label">Jenis Kelamin</label>
                    <select name="jk" class="form-control" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="confirm-password">Konfirmasi Kata Sandi</label>
                    <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                </div>

                <!-- Tombol Update -->
                <div class="text-center">
                    <button type="submit" id="submit-button" class="btn btn-success w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });

    $(document).on('change', '#fakultas-select', function () {
        const fakultasId = $(this).val();
        $('#jurusan-select').html('<option value="">Loading...</option>');
        if (fakultasId) {
            $.get(`/get-jurusan/${fakultasId}`, function (data) {
                let options = '<option value="">Pilih Jurusan</option>';
                data.forEach(function (jurusan) {
                    options += `<option value="${jurusan.id}">${jurusan.nama}</option>`;
                });
                $('#jurusan-select').html(options);
            });
        } else {
            $('#jurusan-select').html('<option value="">Pilih Jurusan</option>');
        }
    });
</script>

<script>
    document.getElementById('password-form').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // Cek apakah password dan confirm password cocok
        if (password !== confirmPassword) {
            e.preventDefault(); // Mencegah pengiriman form
            Swal.fire({
                icon: 'error',
                title: 'Kata Sandi Tidak Cocok',
                text: 'Kata Sandi dan Konfirmasi Kata Sandi Tidak Cocok!'
            });
        }
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
