@extends('layouts.app')

@section('css')
    <style>
        /* Badge Styles */
        .priority-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 15px;
            font-weight: 500;
        }

        .badge-rendah {
            background-color: #6c757d;
            color: white;
        }

        .badge-sedang {
            background-color: #fd7e14;
            color: white;
        }

        .badge-tinggi {
            background-color: #dc3545;
            color: white;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 15px;
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

        /* Card Filter */
        .filter-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Table Styles */
        .compact-table {
            font-size: 13px;
        }

        .compact-table thead th {
            font-size: 12px;
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 10px 12px;
        }

        .compact-table tbody td {
            padding: 10px 12px;
            vertical-align: middle;
        }

        /* Action Buttons */
        .btn-action-sm {
            padding: 3px 8px;
            font-size: 12px;
        }

        /* Value Display */
        .fuzzy-value {
            font-size: 14px;
            font-weight: 600;
        }

        .avg-value {
            font-size: 11px;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .compact-table {
                font-size: 12px;
            }

            .compact-table thead th,
            .compact-table tbody td {
                padding: 8px;
            }

            .priority-badge,
            .status-badge {
                font-size: 10px;
                padding: 3px 6px;
            }
        }
    </style>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Laporan /</span> Umpan Balik Penumpang
    </h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('laporan_umpan_balik') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status Perbaikan</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Prioritas</label>
                        <select name="prioritas" class="form-select">
                            <option value="">Semua Prioritas</option>
                            <option value="rendah" {{ request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ request('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Bus</label>
                        <select name="bus" class="form-select">
                            <option value="">Semua Bus</option>
                            @foreach ($bus as $item)
                                <option value="{{ $item->id }}" {{ request('bus') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                     <div class="col-md-3">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-select">
                            <option value="">Semua Target</option>
                            <option value="penumpang" {{ request('target') == 'penumpang' ? 'selected' : '' }}>Penumpang</option>
                            <option value="supir" {{ request('target') == 'supir' ? 'selected' : '' }}>Supir</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-filter"></i> Terapkan Filter
                            </button>
                            <a href="{{ url('laporan_umpan_balik') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-reset"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Laporan</h6>
                            <h3 class="mb-0">{{ $laporan->total() }}</h3>
                        </div>
                        <div class="icon">
                            <i class="bx bx-file fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50">Menunggu</h6>
                            <h3 class="mb-0">
                                {{ DB::table('laporan')->where('status_perbaikan', 'menunggu')->count() }}
                            </h3>
                        </div>
                        <div class="icon">
                            <i class="bx bx-time fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Diproses</h6>
                            <h3 class="mb-0">
                                {{ DB::table('laporan')->where('status_perbaikan', 'diproses')->count() }}
                            </h3>
                        </div>
                        <div class="icon">
                            <i class="bx bx-cog fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Selesai</h6>
                            <h3 class="mb-0">
                                {{ DB::table('laporan')->where('status_perbaikan', 'selesai')->count() }}
                            </h3>
                        </div>
                        <div class="icon">
                            <i class="bx bx-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Laporan</h5>
            <span class="badge bg-primary">Total: {{ $laporan->total() }} data</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover compact-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="100">Tanggal</th>
                            <th>Bus</th>
                            <th>Pertanyaan</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th width="120">Nilai Fuzzy</th>
                            <th width="100">Prioritas</th>
                            <th width="100">Status</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $loop->iteration + $laporan->perPage() * ($laporan->currentPage() - 1) }}</td>
                                <td>
                                    <div class="small">{{ $item->created_at->setTimeZone('Asia/Jakarta')->format('d/m/Y') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        {{ $item->created_at->setTimeZone('Asia/Jakarta')->format('H:i') }}
                                    </div>
                                </td>
                                <td>{{ $item->bus?->nama ?? '-' }}</td>
                                <td>{{ $item->pertanyaan?->teks_pertanyaan ?? '-' }}</td>
                                <td>{{ strtoupper($item->pertanyaan?->kategori ?? '-') }}</td>
                                 <td>

                                    @if ($item->target === 'supir')
                                        <span class="badge bg-info">SUPIR</span>
                                    @elseif($item->target === 'penumpang')
                                        <span class="badge bg-secondary">PENUMPANG</span>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fuzzy-value text-primary">
                                        {{ number_format($item->nilai_fuzzy, 2) }}
                                    </div>
                                </td>
                                <td>
                                    @if ($item->kategori_prioritas == 'tinggi')
                                        <span class="badge priority-badge badge-tinggi">TINGGI</span>
                                    @elseif($item->kategori_prioritas == 'sedang')
                                        <span class="badge priority-badge badge-sedang">SEDANG</span>
                                    @elseif($item->kategori_prioritas == 'rendah')
                                        <span class="badge priority-badge badge-rendah">RENDAH</span>
                                    @else
                                        <span class="badge priority-badge badge-default">-</span>
                                    @endif
                                </td>
                               
                                <td>
                                    @if ($item->status_perbaikan == 'menunggu')
                                        <span class="badge status-badge badge-menunggu">MENUNGGU</span>
                                    @elseif($item->status_perbaikan == 'diproses')
                                        <span class="badge status-badge badge-diproses">DIPROSES</span>
                                    @elseif($item->status_perbaikan == 'selesai')
                                        <span class="badge status-badge badge-selesai">SELESAI</span>
                                    @else
                                        <span class="badge status-badge badge-default">-</span>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('detail_laporan_umpan_balik', $item->id) }}"
                                            class="btn btn-info btn-action-sm" title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Button Modal Update Status -->
                                        <button type="button" class="btn btn-warning btn-action-sm"
                                            data-bs-toggle="modal" data-bs-target="#statusModal{{ $item->id }}"
                                            title="Update Status">
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <!-- Button Delete -->
                                        <button type="button" class="btn btn-danger btn-action-sm btn-delete"
                                            data-url="{{ route('delete_laporan_umpan_balik', $item->id) }}"
                                            title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Update Status -->
                            <div class="modal fade" id="statusModal{{ $item->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Perbarui Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('update_laporan_umpan_balik', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status Perbaikan</label>
                                                    <select name="status_perbaikan" class="form-select" required>
                                                        <option value="menunggu"
                                                            {{ $item->status_perbaikan == 'menunggu' ? 'selected' : '' }}>
                                                            Menunggu</option>
                                                        <option value="diproses"
                                                            {{ $item->status_perbaikan == 'diproses' ? 'selected' : '' }}>
                                                            Diproses</option>
                                                        <option value="selesai"
                                                            {{ $item->status_perbaikan == 'selesai' ? 'selected' : '' }}>
                                                            Selesai</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-inbox fs-1 text-muted mb-2 d-block"></i>
                                    <p class="text-muted">Belum ada data laporan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($laporan->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $laporan->firstItem() }} - {{ $laporan->lastItem() }} dari {{ $laporan->total() }}
                        data
                    </div>
                    <div>
                        {{ $laporan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Auto-close alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Delete confirmation
            $('.btn-delete').on('click', function() {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data laporan yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

    @if (session('success'))
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

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
