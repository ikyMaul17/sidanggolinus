@extends('layouts_supir.app')

@section('css')
<style>
    .nav-tabs .nav-link.active {
        background-color: #5cb85c;
        color: #fff;
    }
    .rating {
        color: #ffcc00;
    }
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
    }
    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffcc00;
    }
    .add-feedback-btn {
        position: fixed;
        bottom: 90px;
        z-index: 1000;
    }
    .feedback-time {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<section class="container-fluid py-3">
    <div class="text-center mb-4">
        <h5>Feedback</h5>
    </div>

    <ul class="nav nav-tabs row mb-3" id="feedbackTab" role="tablist">
        <li class="col-6 text-center" role="presentation">
            <button class="nav-link active w-100" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button" role="tab" aria-controls="mahasiswa" aria-selected="true">Mahasiswa</button>
        </li>
        <li class="col-6 text-center" role="presentation">
            <button class="nav-link w-100" id="kendaraan-tab" data-bs-toggle="tab" data-bs-target="#kendaraan" type="button" role="tab" aria-controls="kendaraan" aria-selected="false">Kendaraan</button>
        </li>
    </ul>

    <div class="tab-content" id="feedbackTabContent">
        <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel" aria-labelledby="mahasiswa-tab">
            <div id="mahasiswaList" class="row"></div>
        </div>
        <div class="tab-pane fade" id="kendaraan" role="tabpanel" aria-labelledby="kendaraan-tab">
            <div id="kendaraanList" class="row"></div>
        </div>
    </div>

    <button class="btn btn-success add-feedback-btn" id="addFeedback" style="right: 50%; transform: translateX(50%);">Tambah Feedback</button>

    <!-- Modal Tambah Feedback -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Tambah Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedbackForm">
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <select id="tipe" name="tipe" class="form-select" required>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="kendaraan">Kendaraan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5">&#9733;</label>
                                <input type="radio" id="star4" name="rating" value="4" required /><label for="star4">&#9733;</label>
                                <input type="radio" id="star3" name="rating" value="3" required /><label for="star3">&#9733;</label>
                                <input type="radio" id="star2" name="rating" value="2" required /><label for="star2">&#9733;</label>
                                <input type="radio" id="star1" name="rating" value="1" required /><label for="star1">&#9733;</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea id="pesan" name="pesan" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const feedbackForm = document.getElementById('feedbackForm');
        const mahasiswaList = document.getElementById('mahasiswaList');
        const kendaraanList = document.getElementById('kendaraanList');

        // Fetch feedback data
        function loadFeedback() {
            fetch('/feedback_supir/data')
                .then(response => response.json())
                .then(data => {
                    mahasiswaList.innerHTML = '';
                    kendaraanList.innerHTML = '';
                    data.forEach(item => {
                        const timeAgo = timeSince(new Date(item.created_at));
                        const card = `
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>${item.user_input}</h6>
                                        <p>${item.pesan}</p>
                                        <div class="rating">${'&#9733;'.repeat(item.rating)}</div>
                                        <div class="feedback-time text-end">${timeAgo}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        if (item.tipe === 'mahasiswa') {
                            mahasiswaList.innerHTML += card;
                        } else if (item.tipe === 'kendaraan') {
                            kendaraanList.innerHTML += card;
                        }
                    });
                });
        }

        // Calculate time since
        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = Math.floor(seconds / 31536000); // tahun

            if (interval >= 1) return `${interval} tahun yang lalu`;

            interval = Math.floor(seconds / 2592000); // bulan
            if (interval >= 1) return `${interval} bulan yang lalu`;

            interval = Math.floor(seconds / 86400); // hari
            if (interval >= 1) return `${interval} hari yang lalu`;

            interval = Math.floor(seconds / 3600); // jam
            if (interval >= 1) return `${interval} jam yang lalu`;

            interval = Math.floor(seconds / 60); // menit
            if (interval >= 1) return `${interval} menit yang lalu`;

            return `baru saja`; // kurang dari 1 menit
        }


        // Submit feedback
        feedbackForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(feedbackForm);
            fetch('/feedback_supir/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadFeedback();
                    feedbackForm.reset();
                    bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
                } else {
                    alert('Gagal menambahkan feedback.');
                }
            });
        });

        // Initialize
        loadFeedback();

        // Show modal
        document.getElementById('addFeedback').addEventListener('click', function () {
            new bootstrap.Modal(document.getElementById('feedbackModal')).show();
        });
    });
</script>
@endsection
