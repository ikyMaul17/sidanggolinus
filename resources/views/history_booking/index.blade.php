@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Booking</h4>

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
                    <th>Penumpang</th>
                    <th>Bus</th>
                    <th>Supir</th>
                    <th>Penjemputan</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_booking as $raw)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $raw->nama_penumpang }}</td>
                    <td>{{ $raw->nama_bus }}</td>
                    <td>{{ $raw->nama_supir }}</td>
                    <td>{{ $raw->halte_penjemputan }}</td>
                    <td>{{ $raw->halte_tujuan }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($raw->created_at)) }}</td>
                    <td>
                        @if ($raw->status == 'pending')
                            <span class="badge rounded-pill bg-warning">Pending</span>
                        @elseif ($raw->status == 'aktif')
                            <span class="badge rounded-pill bg-success">Aktif</span>
                        @elseif ($raw->status == 'selesai')
                            <span class="badge rounded-pill bg-primary">Selesai</span>
                        @elseif ($raw->status == 'cancel')
                            <span class="badge rounded-pill bg-danger">Cancel</span>
                        @endif
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Apply Filter</button>
                <button type="button" class="btn btn-danger" id="exportPdfModal">Export PDF</button>
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

            if (startDate && endDate) {
                window.location.href = "{{ url('history_booking') }}?startDate=" + startDate + "&endDate=" + endDate;
            } else {
                alert('Silakan isi kedua tanggal.');
            }
        });

        $('#exportPdfModal').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                var url = "{{ url('history_booking_pdf') }}?startDate=" + startDate + "&endDate=" + endDate;
                window.location.href = url;
            } else {
                alert('Silakan isi kedua tanggal.');
            }
        });
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