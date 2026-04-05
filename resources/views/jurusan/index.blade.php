@extends('layouts.app')

@section('css')
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Jurusan</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Basic Bootstrap Table -->
<div class="card">
    <div class="table-responsive text-nowrap">

    <button type="button" class="btn btn-primary" style="margin:20px;" data-bs-toggle="modal" data-bs-target="#basicModal">
        <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Data
    </button>

    <table id="myTable" class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Fakultas</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $raw->fakultas->nama }}</td>
                <td>{{ $raw->kode }}</td>
                <td>{{ $raw->nama }}</td>
                <td>
                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                        <span class="tf-icons bx bx-edit"></span>
                    </button>

                    <a href="{{ url('jurusan/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                        <span class="tf-icons bx bx-trash"></span>
                    </a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    <form action="{{ url('jurusan/update', $raw->id) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label">Fakultas</label>
                            <select name="id_fakultas" class="form-control" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $f)
                                    <option value="{{ $f->id }}" {{ $f->id == $raw->id_fakultas ? 'selected' : '' }}>{{ $f->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" class="form-control" value="{{ $raw->kode }}" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required />
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
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
<!--/ Basic Bootstrap Table -->

<!-- Modal Tambah -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Tambah Jurusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <form action="{{ url('jurusan/store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">Fakultas</label>
                <select name="id_fakultas" class="form-control" required>
                    <option value="">Pilih Fakultas</option>
                    @foreach($fakultas as $f)
                        <option value="{{ $f->id }}">{{ $f->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kode</label>
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" required />
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
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

@if(session('error'))
<script>
    Swal.fire({
        title: 'Perhatian!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
