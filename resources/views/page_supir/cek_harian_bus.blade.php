@extends('layouts_supir.app')

@section('css')
<style>
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
    .status-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 20px;
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
</style>
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Pengecekan Harian Bus</h5>
        <p class="text-muted">Bus: {{ $bus->nama }} | Kapasitas: {{ $bus->kapasitas }}</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($alreadyChecked)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Cek Rutin Harian untuk bus ini sudah diselesaikan oleh supir pertama hari ini. Supir berikutnya tidak perlu mengisi lagi dan bisa langsung mengakses Keluhan Layanan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="formCekHarian" action="{{ route('insert_cek_harian_bus') }}" method="POST">
                @csrf
                
                <!-- Informasi Bus (Hidden) -->
                <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                
                <!-- Status Legend -->
                <div class="status-badges-container mb-3">
                    <span class="badge bg-success status-badge">
                        <i class="fas fa-check-circle"></i> Baik
                    </span>
                    <span class="badge bg-danger status-badge">
                        <i class="fas fa-times-circle"></i> Rusak
                    </span>
                </div>

                <!-- Progress -->
                <div class="progress-info mb-3">
                    <i class="fas fa-tasks"></i> 
                    Komponen dicek: <strong><span id="checkedCount">0</span> / {{ $items->count() }}</strong>
                </div>

                <!-- Daftar Item Pengecekan -->
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
                                <!-- Textarea untuk deskripsi kerusakan -->
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

                <!-- Tombol Submit -->
                <div class="text-center">
                    <button type="submit" id="btnSubmit" class="btn btn-primary w-100" {{ $alreadyChecked || $items->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane me-2"></i> Submit Pengecekan
                    </button>
                    <a href="{{ route('home_supir') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('home_supir') }}";
        }
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Gagal!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

<script>
    const totalItems = {{ $items->count() }};

    function updateProgress() {
        const checked = document.querySelectorAll('.item-radio:checked').length / 2;
        // Each item has 2 radios but only 1 can be checked, so count unique items
        const checkedItems = new Set();
        document.querySelectorAll('.item-radio:checked').forEach(radio => {
            checkedItems.add(radio.dataset.itemId);
        });
        document.getElementById('checkedCount').textContent = checkedItems.size;
    }

    // Handle radio button changes
    document.querySelectorAll('.item-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const itemId = this.dataset.itemId;
            const checkItem = document.getElementById('check-item-' + itemId);
            const keteranganDiv = document.getElementById('keterangan-' + itemId);
            const keteranganInput = keteranganDiv.querySelector('textarea');

            // Update visual styling
            checkItem.classList.remove('status-baik', 'status-rusak');

            if (this.value === '1') {
                // Baik selected
                checkItem.classList.add('status-baik');
                keteranganDiv.style.display = 'none';
                keteranganInput.value = '';
                keteranganInput.removeAttribute('required');
            } else if (this.value === '2') {
                // Rusak selected
                checkItem.classList.add('status-rusak');
                keteranganDiv.style.display = 'block';
                keteranganInput.setAttribute('required', 'required');
            }

            updateProgress();
        });
    });

    // Form validation before submit
    document.getElementById('formCekHarian').addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if all items have been selected
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

        // Check all Rusak items have descriptions
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

        // All validations passed, submit
        this.submit();
    });
</script>
@endsection
