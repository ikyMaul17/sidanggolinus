@extends('layouts_supir.app')

@section('css')
<style>
    /* Styling utama */
    .rating-section {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        background-color: #f8f9fa;
    }

    .rating-header {
        background-color: #e9ecef;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .rating-item {
        margin-bottom: 20px;
        padding: 15px;
        background-color: white;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .rating-item label {
        font-weight: 500;
        margin-bottom: 15px;
        display: block;
        font-size: 15px;
        color: #333;
    }

    /* GRID SYSTEM untuk 5 rating sejajar */
    .rating-options {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .rating-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 12px 5px;
        border-radius: 6px;
        transition: all 0.3s;
        border: 2px solid transparent;
        min-height: 90px;
        background-color: white;
    }

    .rating-option:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .rating-option.selected {
        background-color: #e7f1ff;
        border-color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
    }

    .rating-number {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #495057;
    }

    .rating-text {
        font-size: 11px;
        text-align: center;
        color: #666;
        line-height: 1.3;
        word-wrap: break-word;
        width: 100%;
        padding: 0 2px;
    }

    .rating-option input[type="radio"] {
        display: none;
    }

    /* Badge kategori */
    .badge-kategori {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        margin-left: 10px;
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

    /* Status label untuk rating */
    .rating-value-label {
        font-size: 10px;
        color: #6c757d;
        margin-top: 3px;
        font-weight: 500;
    }

    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .rating-options {
            gap: 5px;
        }

        .rating-option {
            padding: 10px 3px;
            min-height: 80px;
        }

        .rating-number {
            font-size: 18px;
        }

        .rating-text {
            font-size: 10px;
        }

        .rating-item label {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .rating-option {
            min-height: 75px;
            padding: 8px 2px;
        }

        .rating-text {
            font-size: 9px;
        }

        .rating-value-label {
            font-size: 9px;
        }
    }

    .rating-option[data-value="1"] .rating-number { color: #20c997; }
    .rating-option[data-value="2"] .rating-number { color: #198754; }
    .rating-option[data-value="3"] .rating-number { color: #ffc107; }
    .rating-option[data-value="4"] .rating-number { color: #fd7e14; }
    .rating-option[data-value="5"] .rating-number { color: #dc3545; }
</style>
@endsection

@section('content')
<section class="container py-4">
    <div class="text-center mb-4">
        <h4>Form Keluhan Layanan Supir</h4>
        <p class="text-muted">Berikan penilaian Anda terhadap pelayanan bus. Pilih nilai 1-5 untuk setiap pertanyaan.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(! $hasCompletedDailyInspection)
        <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
            <i class="fas fa-exclamation-triangle mt-1"></i>
            <div>
                <strong>Keluhan Layanan terkunci.</strong><br>
                Fitur ini tidak dapat diakses karena Cek Rutin Harian belum selesai. Silakan selesaikan Cek Rutin Harian terlebih dahulu.
            </div>
        </div>
    @endif

    <form action="{{ route('store_umpan_balik_supir') }}" method="POST" id="feedbackForm">
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Bus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Bus Anda</label>
                        <input type="text" class="form-control" value="{{ $bus?->nama ?? '-' }}" readonly>
                        <input type="hidden" name="bus_id" id="bus_id" value="{{ $bus?->id }}">
                    </div>
                </div>
            </div>
        </div>

        <div id="questionsContainer">
            <div class="alert alert-info" id="questionsPlaceholder">
                Memuat pertanyaan untuk bus Anda.
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" {{ ! $hasCompletedDailyInspection ? 'disabled' : '' }}>
                <i class="fas fa-paper-plane me-2"></i>Kirim Keluhan
            </button>
            @if(! $hasCompletedDailyInspection)
                <a href="{{ route('cek_harian_bus') }}" class="btn btn-warning">
                    <i class="fas fa-clipboard-check me-2"></i>Selesaikan Cek Rutin Harian
                </a>
            @endif
            <a href="{{ route('home_supir') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </form>
</section>
@endsection

@section('script')
<script>
    const questionEndpoint = "{{ route('pertanyaan_by_bus_supir') }}";
    const hasCompletedDailyInspection = @json($hasCompletedDailyInspection);
    const ratingLabels = {
        1: 'Sangat Baik',
        2: 'Baik',
        3: 'Cukup',
        4: 'Buruk',
        5: 'Sangat Buruk'
    };
    let totalQuestions = 0;

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderQuestions(data) {
        const container = document.getElementById('questionsContainer');
        const placeholder = document.getElementById('questionsPlaceholder');

        container.querySelectorAll('.rating-section').forEach(section => section.remove());
        totalQuestions = 0;

        const categories = [
            {
                key: 'safety',
                title: 'Kategori Safety (Keselamatan)',
                badgeClass: 'badge-safety',
                badgeText: 'SAFETY',
                prefix: 'safety'
            },
            {
                key: 'operational',
                title: 'Kategori Operational (Operasional)',
                badgeClass: 'badge-operational',
                badgeText: 'OPERATIONAL',
                prefix: 'operational'
            },
            {
                key: 'comfort',
                title: 'Kategori Comfort (Kenyamanan)',
                badgeClass: 'badge-comfort',
                badgeText: 'COMFORT',
                prefix: 'comfort'
            }
        ];

        categories.forEach(category => {
            const items = data[category.key] || [];
            if (items.length === 0) {
                return;
            }

            totalQuestions += items.length;

            const section = document.createElement('div');
            section.className = 'rating-section';
            section.innerHTML = `
                <div class="rating-header">
                    <span>${category.title}</span>
                    <span class="badge ${category.badgeClass} badge-kategori">${category.badgeText}</span>
                </div>
            `;

            items.forEach((item, index) => {
                const ratingOptions = Object.keys(ratingLabels).map(value => {
                    const label = ratingLabels[value];
                    return `
                        <div class="rating-option" onclick="selectRating(this, '${category.prefix}${item.id}', ${value})" data-value="${value}">
                            <input type="radio" name="jawaban[${item.id}]" value="${value}" id="${category.prefix}${item.id}_${value}">
                            <div class="rating-number">${value}</div>
                            <div class="rating-text">${label}</div>
                            <div class="rating-value-label">(${value})</div>
                        </div>
                    `;
                }).join('');

                const itemHtml = `
                    <div class="rating-item">
                        <label>${index + 1}. ${escapeHtml(item.teks_pertanyaan)}</label>
                        <div class="rating-options">
                            ${ratingOptions}
                        </div>
                    </div>
                `;
                section.insertAdjacentHTML('beforeend', itemHtml);
            });

            container.appendChild(section);
        });

        if (placeholder) {
            if (totalQuestions === 0) {
                placeholder.textContent = 'Pertanyaan untuk bus ini belum tersedia.';
                placeholder.classList.remove('alert-info');
                placeholder.classList.add('alert-warning');
                placeholder.style.display = 'block';
            } else {
                placeholder.style.display = 'none';
            }
        }

        applyHoverEffects();
    }

    function applyHoverEffects() {
        const ratingOptions = document.querySelectorAll('.rating-option');
        ratingOptions.forEach(option => {
            option.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.backgroundColor = '#f0f8ff';
                }
            });

            option.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.backgroundColor = '';
                }
            });
        });
    }

    function selectRating(element, name, value) {
        const parent = element.closest('.rating-options');
        const options = parent.querySelectorAll('.rating-option');

        options.forEach(opt => {
            opt.classList.remove('selected');
        });

        element.classList.add('selected');

        const radio = document.getElementById(name + '_' + value);
        if (radio) {
            radio.checked = true;

            element.style.transform = 'scale(1.05)';
            setTimeout(() => {
                element.style.transform = '';
            }, 300);
        }
    }

    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        if (!hasCompletedDailyInspection) {
            e.preventDefault();
            Swal.fire({
                title: 'Fitur Terkunci!',
                text: 'Keluhan Layanan tidak dapat diakses karena Cek Rutin Harian belum selesai.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const busInput = document.getElementById('bus_id');
        if (!busInput.value) {
            e.preventDefault();
            Swal.fire({
                title: 'Peringatan!',
                text: 'Data bus tidak ditemukan.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;

        if (totalQuestions === 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Peringatan!',
                text: 'Pertanyaan untuk bus ini belum tersedia.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (answeredQuestions < totalQuestions) {
            e.preventDefault();
            Swal.fire({
                title: 'Peringatan!',
                html: `Anda belum menjawab semua pertanyaan.<br>
                      <strong>${answeredQuestions} dari ${totalQuestions}</strong> pertanyaan telah dijawab.`,
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                const unansweredItems = document.querySelectorAll('.rating-item');
                for (let item of unansweredItems) {
                    const hasChecked = item.querySelector('input[type="radio"]:checked');
                    if (!hasChecked) {
                        item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        item.style.borderColor = 'red';
                        item.style.boxShadow = '0 0 0 2px rgba(255,0,0,0.1)';
                        setTimeout(() => {
                            item.style.borderColor = '';
                            item.style.boxShadow = '';
                        }, 2000);
                        break;
                    }
                }
            });
            return;
        }

        e.preventDefault();
        Swal.fire({
            title: 'Kirim Penilaian Keluhan?',
            text: 'Apakah Anda yakin ingin mengirim keluhan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim...',
                    text: 'Sedang mengirim keluhan Anda',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    e.target.submit();
                }, 500);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const busInput = document.getElementById('bus_id');
        const placeholder = document.getElementById('questionsPlaceholder');

        if (!hasCompletedDailyInspection) {
            if (placeholder) {
                placeholder.textContent = 'Keluhan Layanan tidak dapat diakses karena Cek Rutin Harian belum selesai.';
                placeholder.classList.remove('alert-info');
                placeholder.classList.add('alert-warning');
            }
            return;
        }

        if (busInput && busInput.value) {
            fetch(`${questionEndpoint}?bus_id=${encodeURIComponent(busInput.value)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load questions');
                }
                return response.json();
            })
            .then(data => {
                renderQuestions(data);
            })
            .catch(() => {
                const placeholder = document.getElementById('questionsPlaceholder');
                if (placeholder) {
                    placeholder.textContent = 'Gagal memuat pertanyaan.';
                    placeholder.classList.remove('alert-info');
                    placeholder.classList.add('alert-warning');
                }
            });
        }

        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const parent = radio.closest('.rating-option');
            if (parent) {
                parent.classList.add('selected');
            }
        });

        applyHoverEffects();
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = "{{ route('list_umpan_balik_supir') }}";
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
@endsection
