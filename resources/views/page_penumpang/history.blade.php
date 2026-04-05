@extends('layouts_penumpang.app')

@section('css')
<!-- Tambahkan CSS khusus di sini jika diperlukan -->
<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-bottom: 20px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .badge-status {
        font-size: 0.875rem;
        padding: 0.4em 0.7em;
        border-radius: 12px;
        display: inline-block;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .card-content {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-bottom: 15px;
    }
    .card-content p {
        margin: 0;
    }
    .card-footer {
        display: flex;
        gap: 10px;
    }

    .card {
        font-size: 16px;
    }
</style>
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center mb-4">
        <h5>Histori Perjalanan</h5>
    </div>

    <!-- Kolom Pencarian -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" id="search-transaksi" class="form-control" placeholder="Cari transaksi...">
                <button class="btn btn-success" id="btn-search">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Transaksi -->
    <div id="transaksi-list">
        <!-- List transaksi akan dimuat di sini melalui AJAX -->
    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transaksiList = document.getElementById('transaksi-list');
        const searchInput = document.getElementById('search-transaksi');
        const searchButton = document.getElementById('btn-search');

        // Fungsi untuk memuat data transaksi
        function loadTransaksi(query = '') {
            fetch(`/history_penumpang/data?search=${query}`)
                .then(response => response.json())
                .then(data => {
                    transaksiList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(transaksi => {
                            const card = document.createElement('div');
                            card.className = 'card';

                            card.innerHTML = `
                                <div class="card-booking"
                                    <div class="card-header">
                                        <h6>Kode: ${transaksi.kode}</h6>
                                    </div>
                                    <div class="card-content">
                                        <p><strong>Nama Bus:</strong> ${transaksi.nama_bus}</p>
                                        <p><strong>Halte:</strong> ${transaksi.halte_penjemputan} - ${transaksi.halte_tujuan}</p>
                                    </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                        <span class="text-muted text-start" style="font-size: 0.875rem;">${transaksi.created_at}</span>
                                        <a href="/book/${transaksi.kode}" class="btn-success btn btn-sm">Book Lagi</a>
                                    </div>
                                </div>
                            `;
                            transaksiList.appendChild(card);
                        });
                    } else {
                        transaksiList.innerHTML = '<p class="text-center">Tidak ada transaksi ditemukan.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Event listener untuk pencarian
        searchButton.addEventListener('click', () => {
            const query = searchInput.value;
            loadTransaksi(query);
        });

        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value;
                loadTransaksi(query);
            }
        });

        // Muat data transaksi saat halaman pertama kali dimuat
        loadTransaksi();
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

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Peringatan!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
