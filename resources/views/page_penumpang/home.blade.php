@extends('layouts_penumpang.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection

@section('content')
<!-- Carousel Slider -->
<section class="carousel mt-2">
    <div id="carouselExample" class="carousel slide shadow-sm" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($announcement as $index => $raw)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ $raw->image }}" class="d-block mx-auto" alt="{{ $raw->caption }}">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Selanjutnya</span>
        </button>
    </div>
</section>

<!-- Card Menu -->
<section class="container py-3">
    <div class="row g-3">
    <div class="col-6">
        <a href="{{ route('booking') }}" id="bookingLink" class="text-decoration-none">
            <div class="card border-1 shadow-sm card-menu text-center">
            <i class="fas fa-map mt-3"></i>
            <p class="mt-2">Pesan</p>
            </div>
        </a>
    </div>
    <div class="col-6">
    <a href="{{ route('history_penumpang') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-calendar-alt mt-3"></i>
        <p class="mt-2">Histori</p>
        </div>
    </a>
    </div>
    <div class="col-6">
    <a href="{{ route('feedback_penumpang') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-comments mt-3"></i>
        <p class="mt-2">Umpan Balik</p>
        </div>
    </a>
    </div>
    <div class="col-6">
    <a href="{{ route('tutorial_penumpang') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-hand-pointer mt-3"></i>
        <p class="mt-2">Tutorial</p>
        </div>
    </a>
    </div>

    <div class="col-6">
    <a href="{{ route('umpan_balik_penumpang') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-cloud-download mt-3"></i>
        <p class="mt-2">Keluhan Layanan</p>
        </div>
    </a>
    </div>

    <div class="col-6">
    <a href="{{ route('list_umpan_balik_penumpang') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-table mt-3"></i>
        <p class="mt-2">Riwayat Hasil Keluhan</p>
        </div>
    </a>
    </div>

    </div>
</section>

<!-- FAQ Section -->
<section class="container py-3">
    <h3 class="text-center">Q&A</h3>
    <div class="accordion" id="faqAccordion">
        @foreach($faq as $index => $item)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index + 1 }}">
                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index + 1 }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index + 1 }}">
                        {{ $item->question }}
                    </button>
                </h2>
                <div id="collapse{{ $index + 1 }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index + 1 }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ $item->answer }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Author Section -->
<section class="container text-center py-4">
    <div class="d-flex justify-content-center align-items-center">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Logo_of_North_Sumatra_University.svg/800px-Logo_of_North_Sumatra_University.svg.png" alt="USU Logo" width="60" height="60" class="me-3">
        <p class="mb-0">Dibuat Oleh Winda Rinjani Adhana</p>
    </div>
</section>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const halteData = @json($halte_pergi); // Get the $halte_pergi data (array of halt info)
        const radiusLimit = 50; // 50 meters

        // Operational hours: 07:00 - 16:00
        const startHour = 7;  // 07:00 AM
        const endHour = 23;   // 04:00 PM

        // Function to calculate distance between two coordinates using Haversine formula
        // function calculateDistance(lat1, lng1, lat2, lng2) {
        //     const R = 6371; // Radius of the Earth in kilometers
        //     const dLat = (lat2 - lat1) * Math.PI / 180;
        //     const dLng = (lng2 - lng1) * Math.PI / 180;
        //     const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        //               Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        //               Math.sin(dLng / 2) * Math.sin(dLng / 2);
        //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        //     const distance = R * c * 1000; // Convert to meters
        //     return distance;
        // }

        // Use the browser's geolocation to get the current position
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;

                // Get the current time
                const currentTime = new Date();
                const currentHour = currentTime.getHours();
                const currentMinute = currentTime.getMinutes();

                // Check if the current time is within operational hours
                const isWithinOperationalHours = currentHour >= startHour && currentHour < endHour;

                // Check booking link click event
                const bookingLink = document.getElementById('bookingLink');
                bookingLink.addEventListener('click', function(e) {
                    // If not within operational hours
                    if (!isWithinOperationalHours) {
                        e.preventDefault(); // Prevent the link from being followed
                        Swal.fire({
                            title: 'Operasional Tutup',
                            text: 'Booking hanya dapat dilakukan antara jam 07:00 - 16:00.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return; // Exit the function
                    }

                    // Check if the user is within 50 meters of any halte
                    let isValidLocation = false;

                    // Loop through all halte data
                    for (let i = 0; i < halteData.length; i++) {
                        const halte = halteData[i];
                        const distance = calculateDistance(currentLat, currentLng, halte.latitude, halte.longitude);
                        
                        if (distance <= radiusLimit) {
                            isValidLocation = true;
                            break;
                        }
                    }

                    if (!isValidLocation) {
                        e.preventDefault(); // Prevent the link from being followed
                        Swal.fire({
                            title: 'Lokasi Terlalu Jauh!',
                            text: 'Lokasi Anda lebih dari 50 meter dari halte terdekat.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    setInterval(checkTransactionStatus, 5000); // Cek setiap 5 detik

    function checkTransactionStatus() {
        fetch("{{ route('check_transaction_status') }}")
            .then(response => response.json())
            .then(data => {
                console.log("Response:", data);
                if (data.show_alert) {
                    console.log("Menjalankan SweetAlert...");
                    Swal.fire({
                        title: "Terima Kasih!",
                        text: "Apakah Anda ingin memberikan feedback?",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "Beri Feedback",
                        cancelButtonText: "Nanti Saja",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim request update updated_at
                            fetch("{{ route('update.transaction') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log("Transaksi berhasil diupdate.");
                                    window.location.href = "{{ route('feedback_penumpang') }}";
                                } else {
                                    console.log("Tidak ada transaksi yang perlu diupdate.");
                                }
                            })
                            .catch(error => console.error("Error updating transaction:", error));
                        }
                    });
                }
            })
            .catch(error => console.error("Error fetching status:", error));
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let alertShown = false; // Menyimpan status apakah alert sudah ditampilkan

    setInterval(checkTransactionStatus, 5000); // Cek status transaksi setiap 5 detik

    function checkTransactionStatus() {
        if (alertShown) return; // Jika alert sudah pernah muncul, hentikan pengecekan lebih lanjut

        fetch("{{ route('check_transaction_status_konfirmasi') }}")
            .then(response => response.json())
            .then(data => {
                console.log("Response:", data);
                if (data.show_alert) {
                    console.log("Menjalankan SweetAlert...");
                    alertShown = true; // Tandai bahwa alert sudah ditampilkan

                    let timeoutId; // Simpan timeout agar bisa dibatalkan jika user merespons lebih cepat

                    Swal.fire({
                        title: "Apakah kamu sudah naik?",
                        text: "Konfirmasi dalam 15 detik, jika tidak maka status akan dibatalkan.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Iya",
                        cancelButtonText: "Tidak",
                        allowOutsideClick: false,
                        timer: 15000,
                        timerProgressBar: true
                    }).then((result) => {
                        clearTimeout(timeoutId); // Hentikan auto-cancel jika user sudah merespons lebih cepat

                        if (result.isConfirmed) {
                            updateStatus("true");
                        } else if (result.dismiss === "timer") {
                            console.log("User tidak merespons dalam 15 detik. Mengubah status menjadi cancel.");
                            updateStatus("cancel");
                        } else {
                            updateStatus("cancel");
                        }
                    });
                }
            })
            .catch(error => console.error("Error fetching status:", error));
    }

    function updateStatus(status) {
        fetch("{{ route('update.transaction_konfirmasi') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status }) // Kirim status yang dipilih user
        })
        .then(response => response.json())
        .catch(error => console.error("Error updating transaction:", error));
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    setInterval(checkTransactionReminder, 5000); // Cek setiap 5 detik

    function checkTransactionReminder() {
        fetch("{{ route('check_transaction_reminder') }}")
            .then(response => response.json())
            .then(data => {
                console.log("Response:", data);
                if (data.show_alert) {
                    console.log("Menjalankan SweetAlert...");
                    Swal.fire({
                        title: "Perhatian!",
                        text: "Estimasi Bus 5 menit lagi akan segera sampai nih, segera bersiap dan menunggu di halte ya!",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "Oke",
                        cancelButtonText: "Tutup",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim request update updated_at
                            fetch("{{ route('update.transaction_reminder') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log("Transaksi berhasil diupdate.");
                                } else {
                                    console.log("Tidak ada transaksi yang perlu diupdate.");
                                }
                            })
                            .catch(error => console.error("Error updating transaction:", error));
                        }
                    });
                }
            })
            .catch(error => console.error("Error fetching status:", error));
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    setInterval(checkTransactionKendala, 5000); // Cek setiap 5 detik

    function checkTransactionKendala() {
        fetch("{{ route('check_transaction_kendala') }}")
            .then(response => response.json())
            .then(data => {
                console.log("Response:", data);
                if (data.show_alert) {
                    console.log("Menjalankan SweetAlert...");
                    Swal.fire({
                        title: "Perhatian!",
                        text: "Mohon maaf perjalanan anda terganggu, silahkan lakukan permintaan penjemputan kembali di halte terdekat !",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "Oke",
                        cancelButtonText: "Tutup",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim request update updated_at
                            fetch("{{ route('update.transaction_kendala') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log("Transaksi berhasil diupdate.");
                                } else {
                                    console.log("Tidak ada transaksi yang perlu diupdate.");
                                }
                            })
                            .catch(error => console.error("Error updating transaction:", error));
                        }
                    });
                }
            })
            .catch(error => console.error("Error fetching status:", error));
    }
});
</script>

@if(session('error'))
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
