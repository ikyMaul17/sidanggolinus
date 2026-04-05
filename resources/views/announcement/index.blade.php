@extends('layouts.app')

@section('css')
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Announcement</h4>

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
            <th>Keterangan</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $raw->caption }}</td>
                <td><img src="{{ $raw->image }}" alt="Image" width="100"></td>
                <td>
                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                        <span class="tf-icons bx bx-edit"></span>
                    </button>

                    <a href="{{ url('announcement/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                        <span class="tf-icons bx bx-trash"></span>
                    </a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pengumuman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('announcement/update', $raw->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="caption" class="form-label">Caption</label>
                                        <input type="text" name="caption" class="form-control" value="{{ $raw->caption }}" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" name="image" class="form-control" onchange="previewImage(event)" />
                                        <img id="preview{{ $raw->id }}" src="{{ $raw->image }}" alt="Image" width="100" class="mt-2">
                                    </div>
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
            <!-- End Modal Edit -->

            @endforeach
        </tbody>
      </table>
    </div>
</div>
<!--/ Basic Bootstrap Table -->

<!-- Modal Add -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Tambah Pengumuman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <form action="{{ url('announcement/store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col mb-3">
                <label for="caption" class="form-label">Keterangan</label>
                <input type="text" name="caption" class="form-control" placeholder="Masukkan Caption" required />
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <input type="file" name="image" class="form-control" id="imageInput" onchange="previewImage(event)" />
                    <div class="mt-2" id="imagePreview">No Gambar</div>
                </div>
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

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });

    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid" style="max-height: 200px;"/>';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = 'No Image';
        }
    }
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
