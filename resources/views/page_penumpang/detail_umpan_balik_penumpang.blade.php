@extends('layouts_penumpang.app')

@section('css')
<style>
    /* Mobile-first design */
    .rating-display {
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-left: 4px solid #0d6efd;
    }
    
    .rating-number {
        font-size: 24px;
        font-weight: bold;
        color: #0d6efd;
        min-width: 50px;
        text-align: center;
    }
    
    .rating-info {
        flex: 1;
        padding-left: 12px;
    }
    
    .rating-label {
        font-size: 13px;
        color: #495057;
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .rating-description {
        font-size: 11px;
        color: #6c757d;
    }
    
    /* Card adjustments */
    .mobile-card {
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        background: #fff;
    }
    
    .card-header-mobile {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 15px;
        border-radius: 10px 10px 0 0;
    }
    
    .card-body-mobile {
        padding: 15px;
    }
    
    /* Info section */
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: #495057;
        font-size: 14px;
    }
    
    .info-value {
        color: #212529;
        font-size: 14px;
        text-align: right;
        max-width: 60%;
    }
    
    /* Progress bars */
    .progress-container {
        margin-bottom: 15px;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 13px;
    }
    
    .progress-value {
        font-weight: 600;
        color: #0d6efd;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
    }
    
    /* Status badges */
    .status-badge-mobile {
        display: block;
        text-align: center;
        padding: 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .badge-high {
        background-color: #ffe6e6;
        color: #dc3545;
        border: 1px solid #f8d7da;
    }
    
    .badge-medium {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .badge-low {
        background-color: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }
    
    .badge-waiting {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .badge-process {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .badge-done {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-cancel {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Section headers */
    .section-header {
        background: linear-gradient(90deg, #e9ecef, #f8f9fa);
        padding: 8px 15px;
        border-radius: 6px;
        margin: 15px 0 10px 0;
        font-weight: 600;
        font-size: 14px;
        color: #495057;
    }
    
    /* Empty state */
    .empty-rating {
        text-align: center;
        padding: 20px;
        color: #6c757d;
        font-style: italic;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .rating-display {
            padding: 8px 12px;
        }
        
        .rating-number {
            font-size: 20px;
            min-width: 40px;
        }
        
        .card-header-mobile {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .card-body-mobile {
            padding: 12px;
        }
        
        .info-item {
            padding: 8px 0;
        }
        
        .info-label, .info-value {
            font-size: 13px;
        }
    }
</style>
@endsection

@section('content')
<section class="container py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Detail Laporan Keluhan</h5>
            <p class="text-muted small mb-0">
                <i class="fas fa-hashtag me-1"></i>{{ $laporan->id }} | 
                <i class="far fa-calendar me-1 ms-2"></i>{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route('list_umpan_balik_penumpang') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
            <span class="d-none d-sm-inline"> Kembali</span>
        </a>
    </div>

    <!-- Info Bus -->
    <div class="mobile-card">
        <div class="card-header-mobile">
            <i class="fas fa-bus me-2"></i>Informasi Bus
        </div>
        <div class="card-body-mobile">
            <div class="info-item">
                <span class="info-label">Nama Bus</span>
                <span class="info-value">{{ $laporan->laporan?->bus->nama ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kapasitas</span>
                <span class="info-value">{{ $laporan->laporan?->bus->kapasitas ?? '-' }} orang</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Lapor</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>


    <!-- Info Pertanyaan -->
    <div class="mobile-card">
        <div class="card-header-mobile">
            <i class="fas fa-bus me-2"></i>Informasi Pertanyaan
        </div>
        <div class="card-body-mobile">
            <div class="info-item">
                <span class="info-label">Teks Pertanyaan</span>
                <span class="info-value">{{ $laporan->laporan?->pertanyaan->teks_pertanyaan ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kategori</span>
                <span class="info-value">{{strtoupper($laporan->laporan?->pertanyaan->kategori ?? '-') }}</span>
            </div>
        </div>
    </div>


    <!-- Status Laporan -->
    <div class="mobile-card">
        <div class="card-header-mobile">
            <i class="fas fa-info-circle me-2"></i>Status Laporan
        </div>
        <div class="card-body-mobile">
            <div class="mb-3">
                <div>
                    <small class="text-muted d-block mb-1">Status Perbaikan</small>
                    @if($laporan->laporan->status_perbaikan == 'menunggu')
                        <div class="status-badge-mobile badge-waiting">
                            <i class="fas fa-clock me-1"></i> MENUNGGU
                        </div>
                    @elseif($laporan->laporan->status_perbaikan == 'diproses')
                        <div class="status-badge-mobile badge-process">
                            <i class="fas fa-cogs me-1"></i> DIPROSES
                        </div>
                    @elseif($laporan->laporan->status_perbaikan == 'selesai')
                        <div class="status-badge-mobile badge-done">
                            <i class="fas fa-check-circle me-1"></i> SELESAI
                        </div>
                    @else
                        <div class="status-badge-mobile badge-cancel">
                            <i class="fas fa-times-circle me-1"></i> TIDAK ADA
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Detail Penilaian -->
    <div class="mobile-card">
        <div class="card-header-mobile">
            <i class="fas fa-list-check me-2"></i>Detail Penilaian
        </div>
        <div class="card-body-mobile">
            @if($jawaban->count() > 0)
                @foreach($jawaban->groupBy('kategori') as $kategori => $items)
                    <!-- Kategori Header -->
                    <div class="section-header">
                        @if($kategori == 'safety')
                            <i class="fas fa-shield-alt me-2"></i>SAFETY (Keselamatan)
                        @elseif($kategori == 'operational')
                            <i class="fas fa-cogs me-2"></i>OPERATIONAL (Operasional)
                        @else
                            <i class="fas fa-couch me-2"></i>COMFORT (Kenyamanan)
                        @endif
                        <span class="float-end small">{{ $items->count() }} pertanyaan</span>
                    </div>
                    
                    <!-- Items per kategori -->
                    @foreach($items as $jawabanItem)
                    <div class="rating-display">
                        <div class="rating-number">{{ $jawabanItem->nilai }}</div>
                        <div class="rating-info">
                            <div class="rating-label">{{ $jawabanItem->teks_pertanyaan }}</div>
                            <div class="rating-description">
                                <i class="fas fa-star me-1 text-warning"></i>
                                @if($jawabanItem->nilai == 1) 
                                    <span class="text-danger">Sangat Buruk</span>
                                @elseif($jawabanItem->nilai == 2) 
                                    <span class="text-warning">Buruk</span>
                                @elseif($jawabanItem->nilai == 3) 
                                    <span>Netral</span>
                                @elseif($jawabanItem->nilai == 4) 
                                    <span class="text-success">Baik</span>
                                @elseif($jawabanItem->nilai == 5) 
                                    <span class="text-success"><strong>Sangat Baik</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            @else
                <div class="empty-rating">
                    <i class="fas fa-inbox fa-2x mb-3"></i>
                    <p>Tidak ada data penilaian</p>
                </div>
            @endif
        </div>
    </div> --}}

    <!-- Summary -->
    {{-- <div class="mobile-card">
        <div class="card-header-mobile">
            <i class="fas fa-file-alt me-2"></i>Ringkasan
        </div>
        <div class="card-body-mobile">
            <div class="row text-center">
                <div class="col-4">
                    <div class="mb-2">
                        <div class="fw-bold text-primary" style="font-size: 18px;">
                            {{ number_format($laporan->avg_safety, 1) }}
                        </div>
                        <small class="text-muted">Safety</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-2">
                        <div class="fw-bold text-primary" style="font-size: 18px;">
                            {{ number_format($laporan->avg_operational, 1) }}
                        </div>
                        <small class="text-muted">Operational</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-2">
                        <div class="fw-bold text-primary" style="font-size: 18px;">
                            {{ number_format($laporan->avg_comfort, 1) }}
                        </div>
                        <small class="text-muted">Comfort</small>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</section>
@endsection

@section('script')
<script>
    // Add click effect on rating displays
    document.addEventListener('DOMContentLoaded', function() {
        const ratingDisplays = document.querySelectorAll('.rating-display');
        
        ratingDisplays.forEach(rating => {
            rating.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });
        
        // Add scroll to top on page load for better mobile experience
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 100);
    });
</script>
@endsection