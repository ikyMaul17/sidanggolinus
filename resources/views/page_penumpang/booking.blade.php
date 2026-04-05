@extends('layouts_penumpang.app')

@section('css')
@endsection

@section('content')
<!-- Card Menu -->
<section class="container py-3">
    <div class="text-center">
        <h5>Permintaan Penjemputan</h5>
    </div>
    <form action="{{ url('submit_booking') }}" method="POST">
    @csrf
        <div class="mb-3">
            <label for="rute" class="form-label">Pilih Rute</label>
            <select class="form-select" id="rute" name="rute">
                <option value="">Pilih Rute</option>
                <option value="pergi">Pergi</option>
                <option value="pulang">Pulang</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="halte_penjemputan" class="form-label">Halte Penjemputan</label>
            <select class="form-select" id="halte_penjemputan" name="id_penjemputan"></select>
            <option value="">Pilih Halte Penjemputan</option>
        </div>
        <div class="mb-3">
            <label for="halte_tujuan" class="form-label">Halte Tujuan</label>
            <select class="form-select" id="halte_tujuan" name="id_tujuan"></select>
            <option value="">Pilih Halte Tujuan</option>
        </div>

        <div class="mb-3">
            <label for="estimasi_waktu" class="form-label">Estimasi Waktu</label>
            <input type="number" class="form-control" id="estimasiWaktu" name="estimasi" readonly>
            <small class="form-text text-muted">Estimasi waktu otomatis dihitung setelah memilih halte.</small>
        </div>

        <button type="submit" class="btn btn-primary w-100">Kirim Pemesanan</button>
    </form>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#rute').change(function() {
            const rute = $(this).val();
            $.post('/get-halte', { rute }, function(data) {
                const haltePenjemputan = $('#halte_penjemputan');
                haltePenjemputan.empty();
                haltePenjemputan.append('<option value="">Pilih Halte Penjemputan</option>');
                data.forEach(halte => haltePenjemputan.append(`<option value="${halte.id}">${halte.nama}</option>`));
            });
        });

        $('#halte_penjemputan').change(function() {
            const rute = $('#rute').val();
            const idPenjemputan = $(this).val();
            $.post('/get-halte-tujuan', { rute, id_penjemputan: idPenjemputan }, function(data) {
                const halteTujuan = $('#halte_tujuan');
                halteTujuan.empty();
                halteTujuan.append('<option value="">Pilih Halte Tujuan</option>');
                data.forEach(halte => halteTujuan.append(`<option value="${halte.id}">${halte.nama}</option>`));
            });

            $.post('/get-estimasi', { rute, id_penjemputan: idPenjemputan }, function(data) {
                if (data.error) {
                    //alert(data.error);
                    Swal.fire({
                        title: 'Pemberitahuan!',
                        text: data.error,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    $('#estimasiWaktu').val(data.estimasi);
                }
            });
        });

        $('#bookingForm').submit(function(e) {
            e.preventDefault();
            alert('Booking berhasil!');
        });
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
