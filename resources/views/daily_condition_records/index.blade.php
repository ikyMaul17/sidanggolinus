@extends('layouts.app')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kondisi Harian</span> Catatan</h4>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/daily_condition_records') }}" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Filter Kondisi</label>
                <select name="filter" class="form-select">
                    <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="baik" {{ $filter == 'baik' ? 'selected' : '' }}>Baik (Semua Komponen Baik)</option>
                    <option value="rusak" {{ $filter == 'rusak' ? 'selected' : '' }}>Rusak (Ada Komponen Rusak)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive text-nowrap">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Inspeksi</th>
                    <th>Bus</th>
                    <th>Supir</th>
                    <th>Status</th>
                    <th>Checklist</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inspections as $inspection)
                @php
                    $results = $resultsByInspection[$inspection->id] ?? collect();
                    $hasRusak = $results->contains('status', 2);
                    $rusakCount = $results->where('status', 2)->count();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($inspection->inspected_at)) }}</td>
                    <td>{{ $inspection->nama_bus }}</td>
                    <td>{{ $inspection->nama_supir }}</td>
                    <td>
                        @if($hasRusak)
                            <span class="badge bg-danger">{{ $rusakCount }} Rusak</span>
                        @else
                            <span class="badge bg-success">Semua Baik</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $inspection->id }}">
                            Lihat
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modals placed outside the table to avoid invalid HTML nesting --}}
    @foreach ($inspections as $inspection)
    <div class="modal fade" id="detailModal{{ $inspection->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checklist Inspeksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Tanggal:</strong> {{ date('d-m-Y H:i:s', strtotime($inspection->inspected_at)) }}
                    </div>
                    <div class="mb-3">
                        <strong>Bus:</strong> {{ $inspection->nama_bus }}
                    </div>
                    <div class="mb-3">
                        <strong>Supir:</strong> {{ $inspection->nama_supir }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (($resultsByInspection[$inspection->id] ?? collect()) as $result)
                                    <tr>
                                        <td>{{ $result->nama }}</td>
                                        <td>
                                            @if($result->status == 1)
                                                <span class="badge bg-success">Baik</span>
                                            @elseif($result->status == 2)
                                                <span class="badge bg-danger">Rusak</span>
                                            @else
                                                <span class="badge bg-secondary">Belum Dicek</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($result->status == 2 && $result->keterangan_rusak)
                                                <span class="text-danger">{{ $result->keterangan_rusak }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if (!isset($resultsByInspection[$inspection->id]))
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data checklist.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
@endsection
