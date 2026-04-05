@extends('layouts.app')

@section('css')
    <style>
        .kategori-badge {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-safety {
            background-color: #dc3545;
            color: white;
        }

        .badge-operational {
            background-color: #0d6efd;
            color: white;
        }

        .badge-comfort {
            background-color: #198754;
            color: white;
        }

        .status-badge {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-aktif {
            background-color: #198754;
            color: white;
        }

        .badge-nonaktif {
            background-color: #6c757d;
            color: white;
        }
    </style>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Pertanyaan Survey</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="table-responsive text-nowrap">

            <button type="button" class="btn btn-primary" style="margin:20px;" data-bs-toggle="modal"
                data-bs-target="#basicModal">
                <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Pertanyaan
            </button>

            {{-- Tambahkan filter di atas tabel --}}
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <form method="GET" action="{{ url('pertanyaan') }}">
                            <div class="input-group">
                                <select name="kategori" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="safety" {{ request('kategori') == 'safety' ? 'selected' : '' }}>Safety
                                    </option>
                                    <option value="operational"
                                        {{ request('kategori') == 'operational' ? 'selected' : '' }}>Operational</option>
                                    <option value="comfort" {{ request('kategori') == 'comfort' ? 'selected' : '' }}>Comfort
                                    </option>
                                </select>
                                <button class="btn btn-outline-primary" type="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <table id="myTable" class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Target Pengguna</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($data as $raw)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($raw->kategori == 'safety')
                                    <span class="badge kategori-badge badge-safety">SAFETY</span>
                                @elseif($raw->kategori == 'operational')
                                    <span class="badge kategori-badge badge-operational">OPERATIONAL</span>
                                @else
                                    <span class="badge kategori-badge badge-comfort">COMFORT</span>
                                @endif
                            </td>
                            <td>{{ $raw->teks_pertanyaan }}</td>
                            <td>
                                @php
                                    $targets = $raw->target_pengguna ?? [];
                                @endphp
                                @if (empty($targets))
                                    -
                                @else
                                    @foreach ($targets as $target)
                                        @if ($target === 'supir')
                                            <span class="badge bg-info">SUPIR</span>
                                        @elseif($target === 'penumpang')
                                            <span class="badge bg-secondary">PENUMPANG</span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($raw->status == 'aktif')
                                    <span class="badge status-badge badge-aktif">AKTIF</span>
                                @else
                                    <span class="badge status-badge badge-nonaktif">NONAKTIF</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $raw->id }}">
                                    <span class="tf-icons bx bx-edit"></span>
                                </button>

                                <a href="{{ url('pertanyaan/delete', $raw->id) }}" class="btn btn-icon btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                    <span class="tf-icons bx bx-trash"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    @foreach ($data as $raw)
        <!-- Modal Edit -->
        <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pertanyaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ url('pertanyaan/update', $raw->id) }}" method="post">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="safety" {{ $raw->kategori == 'safety' ? 'selected' : '' }}>Safety
                                        (Keselamatan)</option>
                                    <option value="operational" {{ $raw->kategori == 'operational' ? 'selected' : '' }}>
                                        Operational (Operasional)</option>
                                    <option value="comfort" {{ $raw->kategori == 'comfort' ? 'selected' : '' }}>Comfort
                                        (Kenyamanan)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teks Pertanyaan</label>
                                <textarea name="teks_pertanyaan" class="form-control" rows="3" required>{{ $raw->teks_pertanyaan }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" {{ $raw->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $raw->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Target Pengguna</label>
                                @php
                                    $selectedTargets = $raw->target_pengguna ?? [];
                                @endphp
                                <select class="target-select2-edit form-control" name="target_pengguna[]"
                                    multiple="multiple" data-placeholder="Pilih target pengguna..." required>
                                    <option value="supir" {{ in_array('supir', $selectedTargets) ? 'selected' : '' }}>
                                        Supir</option>
                                    <option value="penumpang"
                                        {{ in_array('penumpang', $selectedTargets) ? 'selected' : '' }}>Penumpang</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bus</label>
                                <select class="bus-select2-edit form-control" name="bus_ids[]" multiple="multiple"
                                    data-placeholder="Pilih bus...">
                                    @php
                                        $selectedBuses = $raw->bus->pluck('id')->toArray();
                                    @endphp
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}"
                                            {{ in_array($bus->id, $selectedBuses) ? 'selected' : '' }}>
                                            {{ $bus->nama }} ({{ $bus->plat_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Edit -->
    @endforeach

    <!-- Modal Tambah -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pertanyaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ url('pertanyaan/store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <option value="safety">Safety (Keselamatan)</option>
                                <option value="operational">Operational (Operasional)</option>
                                <option value="comfort">Comfort (Kenyamanan)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teks Pertanyaan</label>
                            <textarea name="teks_pertanyaan" class="form-control" rows="3" placeholder="Masukkan teks pertanyaan..."
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>

                         <div class="mb-3">
                            <label class="form-label">Target Pengguna</label>
                            <select class="target-select2-edit form-control" name="target_pengguna[]" multiple="multiple" data-placeholder="Pilih target pengguna..." required>
                                <option value="supir">Supir</option>
                                <option value="penumpang">Penumpang</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bus</label>
                            <select class="bus-select2 form-control" name="bus_ids[]" multiple="multiple"
                                data-placeholder="Pilih bus...">
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->nama }} ({{ $bus->plat_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#myTable').DataTable({
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            $('.modal').on('shown.bs.modal', function() {
                $(this).find('.bus-select2, .bus-select2-edit, .target-select2, .target-select2-edit').each(
                    function() {
                        $(this).select2({
                            dropdownParent: $(this).closest('.modal'),
                            width: '100%',
                            placeholder: $(this).data('placeholder') || ''
                        });
                    });
            });

            // Saat modal ditutup (cleanup)
            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('.bus-select2, .bus-select2-edit, .target-select2, .target-select2-edit').each(
                    function() {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
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
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
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
