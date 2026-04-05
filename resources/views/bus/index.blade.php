@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Bus</h4>

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
        <button type="button" style="margin:20px;" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addBusModal">
            Tambah Bus
        </button>

        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Plat No</th>
                    <th>Kapasitas</th>
                    <th>Rute</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->nama }}</td>
                    <td>{{ $raw->plat_no }}</td>
                    <td>{{ $raw->kapasitas }}</td>
                    <td>{{ $raw->rute }}</td>
                    <td>{{ $raw->keterangan ?? '-' }}</td>
                    <td>
                        <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}">
                            <span class="tf-icons bx bx-edit"></span>
                        </button>

                        <a href="{{ url('bus/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                            <span class="tf-icons bx bx-trash"></span>
                        </a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ url('bus/update/' . $raw->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Bus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Plat No</label>
                                        <input type="text" name="plat_no" class="form-control" value="{{ $raw->plat_no }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kapasitas</label>
                                        <input type="number" name="kapasitas" class="form-control" value="{{ $raw->kapasitas }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rute</label>
                                        <select name="rute" class="form-select" required>
                                            <option value="" disabled>Pilih Rute</option>
                                            <option value="pergi" {{ $raw->rute == 'pergi' ? 'selected' : '' }}>Pergi</option>
                                            <option value="pulang" {{ $raw->rute == 'pulang' ? 'selected' : '' }}>Pulang</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control">{{ $raw->keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('bus/store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plat No</label>
                        <input type="text" name="plat_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rute</label>
                        <select name="rute" class="form-select" required>
                            <option value="" disabled selected>Pilih Rute</option>
                            <option value="pergi">Pergi</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
