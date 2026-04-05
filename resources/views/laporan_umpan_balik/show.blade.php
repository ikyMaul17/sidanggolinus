@extends('layouts.app')

@section('css')
<style>
    /* Badge Styles */
    .priority-badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: 500;
    }
    .badge-rendah { background-color: #6c757d; color: white; }
    .badge-sedang { background-color: #fd7e14; color: white; }
    .badge-tinggi { background-color: #dc3545; color: white; }
    
    .status-badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: 500;
    }
    .badge-menunggu { background-color: #ffc107; color: black; }
    .badge-diproses { background-color: #0dcaf0; color: white; }
    .badge-selesai { background-color: #198754; color: white; }
    
    /* Rating Display */
    .rating-card .card-header {
        background: linear-gradient(90deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid #eef1f4;
    }

    .rating-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .rating-count {
        font-size: 12px;
        color: #495057;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e9ecef;
        font-weight: 600;
    }

    .rating-list {
        display: grid;
        gap: 12px;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 12px;
        background-color: #ffffff;
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid #eef1f4;
        border-left: 4px solid #0d6efd;
        box-shadow: 0 1px 0 rgba(16, 24, 40, 0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .rating-display:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 24, 40, 0.06);
    }

    .rating-number {
        font-size: 18px;
        font-weight: 700;
        color: #0d6efd;
        min-width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #eef4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
    .rating-info {
        flex: 1;
    }
    
    .rating-question {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .rating-user {
        font-size: 14px;
        color: #212529;
        font-weight: 600;
    }

    .rating-email {
        font-size: 12px;
        color: #6c757d;
        padding: 2px 8px;
        border-radius: 12px;
        background-color: #e9ecef;
        line-height: 1.4;
    }
    
    .rating-category {
        font-size: 11px;
        color: #6c757d;
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        background-color: #e9ecef;
        margin-right: 8px;
    }
    
    .rating-value {
        font-size: 13px;
        color: #495057;
    }

    .rating-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 6px;
        margin-top: 4px;
        font-size: 12px;
        color: #6c757d;
    }

    .rating-time {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .rating-display.rating-1 { border-left-color: #198754; }
    .rating-display.rating-1 .rating-number { color: #198754; background: #eaf7f0; }
    .rating-display.rating-2 { border-left-color: #0d6efd; }
    .rating-display.rating-2 .rating-number { color: #0d6efd; background: #eef4ff; }
    .rating-display.rating-3 { border-left-color: #6c757d; }
    .rating-display.rating-3 .rating-number { color: #6c757d; background: #f1f3f5; }
    .rating-display.rating-4 { border-left-color: #fd7e14; }
    .rating-display.rating-4 .rating-number { color: #fd7e14; background: #fff2e6; }
    .rating-display.rating-5 { border-left-color: #dc3545; }
    .rating-display.rating-5 .rating-number { color: #dc3545; background: #fdecec; }
    
    /* Progress Bars */
    .progress-container {
        margin-bottom: 15px;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .progress-value {
        font-weight: 600;
        color: #0d6efd;
    }
    
    .progress {
        height: 10px;
        border-radius: 5px;
    }
    
    /* Info Cards */
    .info-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
    }
    
    .info-title {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #212529;
    }
    
    /* Section Headers */
    .section-header {
        background: linear-gradient(90deg, #e9ecef, #f8f9fa);
        padding: 10px 15px;
        border-radius: 6px;
        margin: 20px 0 15px 0;
        font-weight: 600;
        font-size: 16px;
        color: #495057;
        /* border-left: 4px solid #0d6efd; */
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .rating-display {
            padding: 10px 12px;
        }
        
        .rating-number {
            font-size: 16px;
            min-width: 40px;
            height: 40px;
        }
        
        .rating-user {
            font-size: 13px;
        }

        .rating-meta {
            font-size: 11px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Laporan Umpan Balik</h4>
            <p class="text-muted mb-0">
                ID: #{{ $laporan->id }} | 
                Tanggal: {{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i') }}
            </p>
        </div>
        <div>
            <a href="{{ route('laporan_umpan_balik') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column: Information -->
        <div class="col-md-4">
            <!-- Informasi Laporan -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="info-card">
                        <div class="info-title">ID Laporan</div>
                        <div class="info-value">#{{ $laporan->id }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title">Tanggal Laporan</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($laporan->created_at)->format('d F Y H:i') }}
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-title">Total Jawaban</div>
                        <div class="info-value">{{ $laporan->jawaban->count() }}</div>
                        @if($laporan->email_penumpang)
                        <div class="text-muted small mt-1">{{ $laporan->email_penumpang }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Bus -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Bus</h5>
                </div>
                <div class="card-body">
                    <div class="info-card">
                        <div class="info-title">Nama Bus</div>
                        <div class="info-value">{{ $laporan->bus->nama ?? '-' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title">Kapasitas</div>
                        <div class="info-value">{{ $laporan->bus->kapasitas ?? '-' }} orang</div>
                    </div>
                    
                    @if($laporan->nomor_plat)
                    <div class="info-card">
                        <div class="info-title">Nomor Plat</div>
                        <div class="info-value">{{ $laporan->bus ?? '-' }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status & Prioritas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Status & Prioritas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="info-title mb-2">Status Perbaikan</div>
                        @if($laporan->status_perbaikan == 'menunggu')
                            <span class="badge status-badge badge-menunggu">
                                <i class="bx bx-time"></i> MENUNGGU
                            </span>
                        @elseif($laporan->status_perbaikan == 'diproses')
                            <span class="badge status-badge badge-diproses">
                                <i class="bx bx-cog"></i> DIPROSES
                            </span>
                        @elseif($laporan->status_perbaikan == 'selesai')
                            <span class="badge status-badge badge-selesai">
                                <i class="bx bx-check-circle"></i> SELESAI
                            </span>
                        @else
                            <span class="badge status-badge badge-rendah">
                                <i class="bx bx-info-circle"></i> TIDAK ADA
                            </span>
                        @endif
                    </div>
                    
                    <!-- Update Status Form -->
                    <form action="{{ route('update_laporan_umpan_balik', $laporan->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select name="status_perbaikan" class="form-select" required>
                                <option value="menunggu" {{ $laporan->status_perbaikan == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $laporan->status_perbaikan == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $laporan->status_perbaikan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" class="form-control" rows="3" 
                                      placeholder="Tambahkan catatan atau tindakan yang dilakukan...">{{ old('catatan_admin', $laporan->catatan_admin ?? '') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Ratings & Details -->
        <div class="col-md-8">
            <!-- Detail Penilaian -->
            <div class="card rating-card">
                <div class="card-header">
                    <div class="rating-header">
                        <div>
                            <h5 class="mb-0">Detail Penilaian Pertanyaan</h5>
                            <p class="text-muted small mb-0">Semua jawaban untuk pertanyaan berikut</p>
                        </div>
                        <span class="rating-count">{{ $laporan->jawaban->count() }} jawaban</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($laporan->jawaban->count() > 0)
                            <div class="section-header">
                                {{ $laporan->pertanyaan->teks_pertanyaan }}
                            </div>
                            <div class="rating-list">
                                @foreach($laporan->jawaban as $jawabanItem)
                                <div class="rating-display rating-{{ $jawabanItem->nilai }}">
                                    <div class="rating-number">{{ $jawabanItem->nilai }}</div>
                                    <div class="rating-info">
                                        <div class="rating-question">
                                            <span class="rating-user">{{ $jawabanItem->user->nama }}</span>
                                            <span class="rating-email">{{ $jawabanItem->user->email }}</span>
                                        </div>
                                        <div class="rating-meta">
                                            <div class="rating-value">
                                                @if($jawabanItem->nilai == 1) 
                                                    <span class="text-success">Sangat Baik</span>
                                                @elseif($jawabanItem->nilai == 2) 
                                                    <span class="text-primary">Baik</span>
                                                @elseif($jawabanItem->nilai == 3) 
                                                    <span class="text-secondary">Cukup</span>
                                                @elseif($jawabanItem->nilai == 4) 
                                                    <span class="text-warning">Buruk</span>
                                                @elseif($jawabanItem->nilai == 5) 
                                                    <span class="text-danger">Sangat Buruk</span>
                                                @endif
                                            </div>
                                            <div class="rating-time">
                                                {{ \Carbon\Carbon::parse($jawabanItem->created_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-inbox fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data penilaian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Auto-close alert
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK',
        timer: 3000
    });
</script>
@endif
@endsection
