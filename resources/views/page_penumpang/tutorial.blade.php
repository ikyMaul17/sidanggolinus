@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
<style>
    .card-header {
        cursor: pointer;
    }

    .btn-link {
        color: #0d6efd; /* Warna biru primary Bootstrap */
        text-decoration: none;
    }
    .btn-link:hover {
        color: #0a58ca;
    }
</style>
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Tutorial</h5>
    </div>

    <div class="accordion" id="tutorialAccordion">
        @foreach ($tutorial as $index => $item)
        <div class="card">
            <div class="card-header" id="heading{{ $index }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                <h6 class="mb-0">
                    <button class="btn btn-link {{ $index !== 0 ? 'collapsed' : '' }}" type="button">
                        {{ $item['step'] }}
                    </button>
                </h6>
            </div>
            <div id="collapse{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#tutorialAccordion">
                <div class="card-body">
                    {{ $item['deskripsi'] }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection

@section('script')
<!-- Tambahkan script khusus di sini jika diperlukan -->
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
@endsection