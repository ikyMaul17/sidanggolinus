@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Feedback Penumpang</h4>

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
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bx bx-filter-alt"></i> Filter Tanggal
        </button>
    </div>
    <div class="table-responsive text-nowrap">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Peringkat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_feedback as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->user_input }}</td>
                    <td>{{ $raw->tipe }}</td>
                    <td>{{ $raw->pesan }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($raw->created_at)) }}</td>
                    <td>
                        {{ str_repeat('★', $raw->rating) . str_repeat('☆', 5 - $raw->rating) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="startDate" name="startDate">
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                    </div>

                    <div class="mb-3">
                        <label for="tipe">Tipe</label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="apresiasi">Apresiasi</option>
                            <option value="keluhan">Keluhan</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Terapkan Filter</button>
                <button type="button" class="btn btn-danger" id="exportPdfModal">Ekspor PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();

        $('#applyFilter').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var tipe = $('#tipe').val();

            if (startDate && endDate) {
                window.location.href = "{{ url('list_feedback_penumpang') }}?startDate=" + startDate + "&endDate=" + endDate + "&tipe=" + tipe;
            } else {
                alert('Silakan isi kedua tanggal.');
            }
        });

        $('#exportPdfModal').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var tipe = $('#tipe').val();

            if (startDate && endDate) {
                var url = "{{ url('list_feedback_penumpang_pdf') }}?startDate=" + startDate + "&endDate=" + endDate + "&tipe=" + tipe;
                window.location.href = url;
            } else {
                alert('Silakan isi kedua tanggal.');
            }
        });
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
