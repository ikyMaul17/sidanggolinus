@extends('layouts.app')

@section('css')
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Supir</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="table-responsive text-nowrap">
        <button type="button" class="btn btn-primary" style="margin:20px;" data-bs-toggle="modal" data-bs-target="#basicModal">
            <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Data
        </button>

        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>No. WA</th>
                    <th>Nama Bus</th>
                    <th>No BK</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->nama }}</td>
                    <td>{{ $raw->no_wa }}</td>
                    <td>{{ $raw->bus?->nama ?? '-' }}</td>
                    <td>{{ $raw->bus?->plat_no ?? '-' }}</td>
                    <td>{{ $raw->email }}</td>
                    <td>{{ $raw->role }}</td>
                    <td>
                        <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                            <span class="tf-icons bx bx-edit"></span>
                        </button>
                        <a href="{{ url('supir/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                            <span class="tf-icons bx bx-trash"></span>
                        </a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Supir</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('supir/update', $raw->id) }}" method="post">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_wa" class="form-label">No. WA</label>
                                        <input type="text" name="no_wa" class="form-control" value="{{ $raw->no_wa }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Bus</label>
                                        <select name="id_bus" class="form-control" required>
                                            <option value="">Pilih Bus</option>
                                            @foreach($bus as $f)
                                                <option value="{{ $f->id }}" {{ $f->id == $raw->id_bus ? 'selected' : '' }}>{{ $f->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $raw->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal Edit -->
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('supir/store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_wa" class="form-label">No. WA</label>
                        <input type="text" name="no_wa" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bus</label>
                        <select name="id_bus" class="form-control" required>
                            <option value="">Pilih Bus</option>
                            @foreach($bus as $f)
                                <option value="{{ $f->id }}">{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
