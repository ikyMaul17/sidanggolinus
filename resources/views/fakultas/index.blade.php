@extends('layouts.app')

@section('css')
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Fakultas</h4>

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
            <th>Kode</th>
            <th>Nama</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $raw->kode }}</td>
                <td>{{ $raw->nama }}</td>
                <td>
                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}" >
                        <span class="tf-icons bx bx-edit"></span>
                    </button>

                    <a href="{{ url('fakultas/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                        <span class="tf-icons bx bx-trash"></span>
                    </a>

                </td>
            </tr>

            <!-- modal edit -->
            <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Fakultas</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                    </div>
                    <div class="modal-body">

                    <form class="form-horizontal" action="{{ url('fakultas/update', $raw->id)}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {{ method_field('put') }}

                        <div class="row">
                            <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Kode</label>
                            <input type="text" name="kode" class="form-control" value="{{ $raw->kode }}" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <!-- end modal edit -->

            @endforeach
        </tbody>
      </table>
    </div>
</div>
<!--/ Basic Bootstrap Table -->

 <!-- Modal -->
 <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Tambah Fakultas</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" action="{{ url('fakultas/store')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {{ method_field('post') }}

            <div class="row">
                <div class="col mb-3">
                <label for="nameBasic" class="form-label">Kode</label>
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode" required />
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                <label for="nameBasic" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" required />
                </div>
            </div>
        
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Tutup
        </button>
        <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
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