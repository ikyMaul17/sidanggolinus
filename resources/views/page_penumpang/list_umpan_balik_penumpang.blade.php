@extends('layouts_penumpang.app')

@section('css')
<style>
    /* Badge styles */
    .status-badge {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: 500;
    }
    .badge-menunggu { 
        background-color: #ffc107; 
        color: black; 
    }
    .badge-diproses { 
        background-color: #0dcaf0; 
        color: white; 
    }
    .badge-selesai { 
        background-color: #198754; 
        color: white; 
    }
    
    /* Table styles */
    .compact-table {
        font-size: 13px;
    }
    
    .compact-table thead th {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .compact-table tbody td {
        padding: 8px 12px;
        vertical-align: middle;
    }
    
    /* Button styles */
    .btn-compact {
        padding: 3px 8px;
        font-size: 12px;
    }
    
    /* Card header */
    .card-header-sm {
        padding: 10px 15px;
    }
    
    /* Empty state */
    .empty-state {
        padding: 40px 20px;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .compact-table {
            font-size: 12px;
        }
        
        .compact-table thead th,
        .compact-table tbody td {
            padding: 6px 8px;
        }
        
        .status-badge {
            font-size: 9px;
            padding: 2px 6px;
        }
        
        .btn-compact {
            padding: 2px 6px;
            font-size: 11px;
        }
    }
</style>
@endsection

@section('content')
<section class="container-fluid py-3 px-0">
    <div class="text-center mb-3">
        <h4 class="h5 mb-2">List Keluhan Layanan</h4>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div class="small">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card w-100">
        <div class="card-header card-header-sm d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0">Daftar Laporan</h6>
        </div>
        
        <div class="card-body p-2">
            @if($answers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover compact-table mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th>Bus</th>
                            <th>Pertanyaan</th>
                            <th class="text-center" style="width: 100px;">Status</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($answers as $item)
                        <tr>
                            <td class="text-center fw-semibold">
                                {{ $loop->iteration + ($answers->perPage() * ($answers->currentPage() - 1)) }}
                            </td>
                            <td>
                                <div class="small">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
                                </div>
                                
                            </td>
                            <td>
                                <div class="fw-medium">{{ $item->laporan?->bus?->nama ?? '-' }}</div>
                                @if($item->nama_bus)
                                
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $item->laporan?->pertanyaan?->teks_pertanyaan ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                @php
                                    $status = $item->laporan?->status_perbaikan
                                @endphp
                                @if($status == 'menunggu')
                                <span class="badge status-badge badge-menunggu">MENUNGGU</span>
                                @elseif($status == 'diproses')
                                <span class="badge status-badge badge-diproses">DIPROSES</span>
                                @elseif($status == 'selesai')
                                <span class="badge status-badge badge-selesai">SELESAI</span>
                                @else
                                <span class="badge status-badge badge-ditolak">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('detail_umpan_balik_penumpang/' . $item->id) }}" 
                                   class="btn btn-info btn-compact"
                                   title="Lihat Detail">
                                    <span class="d-none d-md-inline ms-1">Detail</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($answers->hasPages())
            <div class="d-flex justify-content-center mt-3 pt-2 border-top">
                <nav aria-label="Page navigation">
                    {{ $laporan->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            @endif
            
            @else
            <div class="empty-state text-center">
                <i class="fas fa-inbox text-muted mb-3"></i>
                <p class="text-muted mb-3">Belum ada riwayat Keluhan</p>
                <a href="{{ route('umpan_balik_penumpang') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Buat Keluhan Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('script')
@if(session('error'))
<script>
    Swal.fire({
        title: 'Gagal!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'small-popup'
        }
    });
</script>
@endif

<script>
    // Optional: Add hover effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('.compact-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Style pagination
        const paginationItems = document.querySelectorAll('.pagination .page-item');
        paginationItems.forEach(item => {
            item.classList.add('page-item-sm');
        });
    });
</script>

<style>
    /* Additional styles for Swal popup */
    .small-popup {
        font-size: 14px;
    }
    
    /* Smaller pagination */
    .page-item-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection
