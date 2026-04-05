@extends('layouts.app')

@section('css')
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Tutorial</h4>

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
            <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Tutorial
        </button>

        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Step</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach($data as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->step }}</td>
                    <td>{{ $raw->deskripsi }}</td>
                    <td>
                        <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                            <span class="tf-icons bx bx-edit"></span>
                        </button>

                        <a href="{{ url('tutorial/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                            <span class="tf-icons bx bx-trash"></span>
                        </a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Tutorial</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('tutorial/update', $raw->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="step" class="form-label">Step</label>
                                            <input type="text" name="step" class="form-control" value="{{ $raw->step }}" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" required>{{ $raw->deskripsi }}</textarea>
                                        </div>
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

<!-- Modal Tambah -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tutorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('tutorial/store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="step" class="form-label">Step</label>
                            <input type="text" name="step" class="form-control" placeholder="Masukkan step" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" placeholder="Masukkan deskripsi" required></textarea>
                        </div>
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
