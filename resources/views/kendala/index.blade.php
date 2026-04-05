@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Kendala</h4>

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
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bus</th>
                    <th>Supir</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_kendala as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->bus->nama }}</td>
                    <td>{{ $raw->supir->nama }}</td>
                    <td>{{ $raw->keterangan }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($raw->created_at)) }}</td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="statusModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('update_status_kendala', $raw->id) }}" method="post">
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
