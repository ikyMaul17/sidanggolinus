@extends('layouts_supir.app')

@section('css')
<style>
    /* Tab Styles */
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 10px 20px;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background: transparent;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 3px solid #dee2e6;
    }
    .tab-content {
        padding-top: 20px;
    }

    /* === Cek Harian Bus Styles === */
    .check-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    .check-item:hover {
        background-color: #f8f9fa;
    }
    .check-item.status-baik {
        background-color: #e8f5e9;
        border-color: #4caf50;
    }
    .check-item.status-rusak {
        background-color: #ffebee;
        border-color: #f44336;
    }
    .item-name {
        font-size: 16px;
        font-weight: 500;
    }
    .status-badges-container {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .radio-group {
        display: flex;
        gap: 15px;
        margin-top: 8px;
    }
    .radio-group .form-check {
        margin: 0;
    }
    .radio-group .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .radio-group .form-check-label {
        cursor: pointer;
        font-weight: 500;
    }
    .keterangan-rusak {
        display: none;
        margin-top: 10px;
    }
    .keterangan-rusak textarea {
        border-color: #f44336;
    }
    .progress-info {
        font-size: 14px;
        color: #666;
    }

    /* === Umpan Balik Styles === */
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
    .btn-compact {
        padding: 3px 8px;
        font-size: 12px;
    }
    .card-header-sm {
        padding: 10px 15px;
    }
    .empty-state {
        padding: 40px 20px;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
    }

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
        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-3">
        <h4 class="h5 mb-2">Supir Panel</h4>
        <p class="text-muted small">Cek harian bus dan Keluhan Layanan</p>
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

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-0" id="supirTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab == 'cek_harian' ? 'active' : '' }}" id="cek-harian-tab"
                data-bs-toggle="tab" data-bs-target="#cek-harian" type="button" role="tab"
                aria-controls="cek-harian" aria-selected="{{ $activeTab == 'cek_harian' ? 'true' : 'false' }}">
                <i class="fas fa-wrench me-1"></i> Cek Harian Bus
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab == 'umpan_balik' ? 'active' : '' }}" id="umpan-balik-tab"
                data-bs-toggle="tab" data-bs-target="#umpan-balik" type="button" role="tab"
                aria-controls="umpan-balik" aria-selected="{{ $activeTab == 'umpan_balik' ? 'true' : 'false' }}">
                <i class="fas fa-comment-dots me-1"></i> Keluhan Layanan
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="supirTabsContent">

        <!-- ========== TAB 1: Cek Harian Bus ========== -->
        <div class="tab-pane fade {{ $activeTab == 'cek_harian' ? 'show active' : '' }}" id="cek-harian" role="tabpanel" aria-labelledby="cek-harian-tab">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h6>Pengecekan Harian Bus</h6>
                        <p class="text-muted small mb-0">Bus: {{ $bus->nama }} | Kapasitas: {{ $bus->kapasitas }}</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($alreadyChecked)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Pengecekan harian hari ini sudah disubmit.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="formCekHarian" action="{{ route('insert_cek_harian_bus') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bus_id" value="{{ $bus->id }}">

                        <div class="status-badges-container mb-3">
                            <span class="badge bg-success status-badge">
                                <i class="fas fa-check-circle"></i> Baik
                            </span>
                            <span class="badge bg-danger status-badge">
                                <i class="fas fa-times-circle"></i> Rusak
                            </span>
                        </div>

                        <div class="progress-info mb-3">
                            <i class="fas fa-tasks"></i>
                            Komponen dicek: <strong><span id="checkedCount">0</span> / {{ $items->count() }}</strong>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-3">Pilih kondisi setiap komponen:</h6>

                            @if($items->isEmpty())
                                <div class="alert alert-warning mb-0">
                                    Tidak ada item inspeksi yang aktif.
                                </div>
                            @else
                                @foreach($items as $item)
                                    <div class="check-item" id="check-item-{{ $item->id }}">
                                        <div>
                                            <span class="item-name">{{ $item->nama }}</span>
                                            @if($item->deskripsi)
                                                <p class="text-muted mb-0 small">{{ $item->deskripsi }}</p>
                                            @endif
                                        </div>
                                        <div class="radio-group">
                                            <div class="form-check">
                                                <input class="form-check-input item-radio" type="radio"
                                                    name="items[{{ $item->id }}][status]"
                                                    id="baik-{{ $item->id }}"
                                                    value="1"
                                                    data-item-id="{{ $item->id }}"
                                                    {{ $alreadyChecked ? 'disabled' : '' }}>
                                                <label class="form-check-label text-success" for="baik-{{ $item->id }}">
                                                    <i class="fas fa-check-circle"></i> Baik
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input item-radio" type="radio"
                                                    name="items[{{ $item->id }}][status]"
                                                    id="rusak-{{ $item->id }}"
                                                    value="2"
                                                    data-item-id="{{ $item->id }}"
                                                    {{ $alreadyChecked ? 'disabled' : '' }}>
                                                <label class="form-check-label text-danger" for="rusak-{{ $item->id }}">
                                                    <i class="fas fa-times-circle"></i> Rusak
                                                </label>
                                            </div>
                                        </div>
                                        <div class="keterangan-rusak" id="keterangan-{{ $item->id }}">
                                            <label class="form-label text-danger small fw-bold">
                                                <i class="fas fa-exclamation-triangle"></i> Jelaskan kondisi kerusakan:
                                            </label>
                                            <textarea class="form-control keterangan-input"
                                                name="items[{{ $item->id }}][keterangan_rusak]"
                                                rows="2"
                                                placeholder="Jelaskan kerusakan yang ditemukan..."
                                                {{ $alreadyChecked ? 'disabled' : '' }}></textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" id="btnSubmit" class="btn btn-primary w-100" {{ $alreadyChecked || $items->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pengecekan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========== TAB 2: Riwayat Umpan Balik ========== -->
        <div class="tab-pane fade {{ $activeTab == 'umpan_balik' ? 'show active' : '' }}" id="umpan-balik" role="tabpanel" aria-labelledby="umpan-balik-tab">
            <div class="card">
                <div class="card-header card-header-sm d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0">Daftar Laporan</h6>
                    @if($hasCompletedDailyInspection)
                        <a href="{{ route('umpan_balik_supir') }}" class="btn btn-primary btn-sm py-1">
                            <i class="fas fa-plus me-1 small"></i>
                            <span class="small">Buat Baru</span>
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary btn-sm py-1" disabled>
                            <i class="fas fa-lock me-1 small"></i>
                            <span class="small">Buat Baru</span>
                        </button>
                    @endif
                </div>

                <div class="card-body p-2">
                    @if(! $hasCompletedDailyInspection)
                        <div class="alert alert-warning d-flex align-items-start gap-2 mx-2 mt-2 mb-3" role="alert">
                            <i class="fas fa-exclamation-triangle mt-1"></i>
                            <div>
                                <strong>Keluhan Layanan terkunci.</strong><br>
                                Fitur ini tidak dapat diakses karena Cek Rutin Harian belum selesai. Silakan selesaikan Cek Rutin Harian terlebih dahulu.
                            </div>
                        </div>
                    @endif
                    @if($answers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover compact-table mb-0">
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
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $item->laporan?->bus?->nama ?? '-' }}</div>
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
                                        <span class="badge status-badge badge-selesai">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('detail_umpan_balik_supir/' . $item->id) }}"
                                           class="btn btn-info btn-compact"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Detail</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($answers->hasPages())
                    <div class="d-flex justify-content-center mt-3 pt-2 border-top">
                        <nav aria-label="Page navigation">
                            {{ $answers->appends(['tab' => 'umpan_balik'])->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                    @endif

                    @else
                    <div class="empty-state text-center">
                        <i class="fas fa-inbox text-muted mb-3"></i>
                        <p class="text-muted mb-3">Belum ada riwayat keluhan</p>
                        @if($hasCompletedDailyInspection)
                            <a href="{{ route('umpan_balik_supir') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Buat Keluhan Pertama
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                <i class="fas fa-lock me-1"></i>
                                Buat Keluhan Pertama
                            </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
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

<script>
    // === Cek Harian Bus Scripts ===
    const totalItems = {{ $items->count() }};

    function updateProgress() {
        const checkedItems = new Set();
        document.querySelectorAll('.item-radio:checked').forEach(radio => {
            checkedItems.add(radio.dataset.itemId);
        });
        document.getElementById('checkedCount').textContent = checkedItems.size;
    }

    document.querySelectorAll('.item-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const itemId = this.dataset.itemId;
            const checkItem = document.getElementById('check-item-' + itemId);
            const keteranganDiv = document.getElementById('keterangan-' + itemId);
            const keteranganInput = keteranganDiv.querySelector('textarea');

            checkItem.classList.remove('status-baik', 'status-rusak');

            if (this.value === '1') {
                checkItem.classList.add('status-baik');
                keteranganDiv.style.display = 'none';
                keteranganInput.value = '';
                keteranganInput.removeAttribute('required');
            } else if (this.value === '2') {
                checkItem.classList.add('status-rusak');
                keteranganDiv.style.display = 'block';
                keteranganInput.setAttribute('required', 'required');
            }

            updateProgress();
        });
    });

    const formCekHarian = document.getElementById('formCekHarian');
    if (formCekHarian) {
        formCekHarian.addEventListener('submit', function(e) {
            e.preventDefault();

            const checkedItems = new Set();
            document.querySelectorAll('.item-radio:checked').forEach(radio => {
                checkedItems.add(radio.dataset.itemId);
            });

            if (checkedItems.size < totalItems) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Semua komponen harus dicek kondisinya (Baik atau Rusak) sebelum submit.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let missingDescription = false;
            document.querySelectorAll('.item-radio[value="2"]:checked').forEach(radio => {
                const itemId = radio.dataset.itemId;
                const keteranganInput = document.querySelector('#keterangan-' + itemId + ' textarea');
                if (!keteranganInput.value.trim()) {
                    missingDescription = true;
                }
            });

            if (missingDescription) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Silakan jelaskan kondisi kerusakan untuk setiap komponen yang Rusak.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            this.submit();
        });
    }

    // === Umpan Balik hover effect ===
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('.compact-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>

<style>
    .small-popup {
        font-size: 14px;
    }
    .page-item-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection
