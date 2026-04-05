@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4">Daftar Penumpang</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Card Table -->
<div class="card">
    <div class="table-responsive text-nowrap">
        <button type="button" class="btn btn-primary" style="margin:20px;" data-bs-toggle="modal" data-bs-target="#addModal">
            <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Penumpang
        </button>

        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No. WA</th>
                    <th>NIM</th>
                    <th>Fakultas</th>
                    <th>Jurusan</th>
                    <th>Jenis Kelamin</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->nama }}</td>
                    <td>{{ $raw->no_wa }}</td>
                    <td>{{ $raw->nim }}</td>
                    <td>{{ $raw->fakultas->nama }}</td>
                    <td>{{ $raw->jurusan->nama ?? '' }}</td>
                    <td>{{ $raw->jk }}</td>
                    <td>{{ $raw->email }}</td>
                    <td>
                        @if($raw->status == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-warning">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-icon btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $raw->id }}">
                            <span class="tf-icons bx bx-toggle-right"></span>
                        </button>
                        <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                            <span class="tf-icons bx bx-edit"></span>
                        </button>
                        <a href="{{ url('penumpang/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <span class="tf-icons bx bx-trash"></span>
                        </a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Penumpang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('penumpang/update', $raw->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <label for="editNama" class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editNoWa" class="form-label">No. WA</label>
                                        <input type="text" name="no_wa" class="form-control" value="{{ $raw->no_wa }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editNim" class="form-label">NIM</label>
                                        <input type="text" name="nim" class="form-control" value="{{ $raw->nim }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editFakultas" class="form-label">Fakultas</label>
                                        <select name="id_fakultas" class="form-control fakultas-select" data-id="{{ $raw->id }}" required>
                                            @foreach($fakultas as $f)
                                            <option value="{{ $f->id }}" {{ $raw->id_fakultas == $f->id ? 'selected' : '' }}>
                                                {{ $f->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editJurusan" class="form-label">Jurusan</label>
                                        <select name="id_jurusan" id="jurusan-{{ $raw->id }}" class="form-control jurusan-select" required>
                                            @if(!is_null($raw->fakultas) && !is_null($raw->fakultas->jurusan))
                                                @foreach($raw->fakultas->jurusan as $j)
                                                    <option value="{{ $j->id }}" {{ $raw->id_jurusan == $j->id ? 'selected' : '' }}>
                                                        {{ $j->nama }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Jurusan tidak tersedia</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editJk" class="form-label">Jenis Kelamin</label>
                                        <select name="jk" class="form-control" required>
                                            <option value="Laki-laki" {{ $raw->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $raw->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editEmail" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $raw->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editPassword" class="form-label">Kata Sandi</label>
                                        <input type="password" name="password" class="form-control">
                                        <small>Kosongkan jika tidak ingin mengubah kata sandi.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editRole" class="form-label">Peran</label>
                                        <input type="text" name="role" class="form-control" value="penumpang" readonly>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Edit Modal -->

                <!-- Edit Modal -->
                <div class="modal fade" id="statusModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('penumpang/status', $raw->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    
                                    <div class="mb-3">
                                        <label for="editJk" class="form-label">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="aktif" {{ $raw->status == 'aktif' ? 'selected' : '' }}>aktif</option>
                                            <option value="tidak aktif" {{ $raw->status == 'tidak aktif' ? 'selected' : '' }}>tidak aktif</option>
                                        </select>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Edit Modal -->
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penumpang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('penumpang/store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="addNama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="addNoWa" class="form-label">No. WA</label>
                        <input type="text" name="no_wa" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="addNim" class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required>
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
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="addRole" class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" value="penumpang" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
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

    $(document).on('change', '.fakultas-select', function () {
        const fakultasId = $(this).val();
        $('.jurusan-select').html('<option value="">Loading...</option>');
        if (fakultasId) {
            $.get(`/get-jurusan/${fakultasId}`, function (data) {
                let options = '<option value="">Pilih Jurusan</option>';
                data.forEach(function (jurusan) {
                    options += `<option value="${jurusan.id}">${jurusan.nama}</option>`;
                });
                $('.jurusan-select').html(options);
            });
        } else {
            $('.jurusan-select').html('<option value="">Pilih Jurusan</option>');
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
