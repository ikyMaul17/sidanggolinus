@extends('layouts_penumpang.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection

@section('content')
<!-- Card Menu -->
<section class="container py-3">
    <div id="map" style="height: 500px;"></div>

    <div class="mt-3 d-flex gap-2">
        <button id="busButton" class="btn btn-success w-50">Bus : -</button>
        <button id="estimasiButton" class="btn btn-success w-50">Estimasi : -</button>
    </div>

    @if($data_pending)
    <div class="mt-2 text-center">
        <a href="{{ url('cancel_request') }}" class="btn btn-danger w-100">Batalkan Permintaan</a>
    </div>
    @endif
</section>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize the map
        var map = L.map('map').setView([0, 0], 2);  // Default coordinates (global view)
        
        // Set up the map tiles (you can use a different tile layer if needed)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Function to add bus markers to the map
        function addBusMarkers(buses, map) {
            var busBounds = []; // To store bounds for buses

            buses.forEach(function(bus) {
                var busMarker = L.marker([bus.latitude, bus.longitude])
                    .addTo(map)
                    .bindPopup('<b>' + bus.nama + '</b>')
                    .openPopup();
                
                // Push each bus marker's position to the busBounds array
                busBounds.push(busMarker.getLatLng());
            });

            return busBounds;  // Return the bounds of all bus markers
        }

        // Function to add bus stops markers to the map
        function addBusStops(stops, map) {
            var stopBounds = []; // To store bounds for bus stops

            stops.forEach(function(stop) {
                var stopMarker = L.marker([stop.latitude, stop.longitude], {icon: L.icon({
                        iconUrl: 'https://cdn4.iconfinder.com/data/icons/small-n-flat/24/map-marker-512.png', // Add a custom icon for the stops
                        iconSize: [50, 50],
                        iconAnchor: [12, 12],
                        popupAnchor: [0, -12]
                    })})
                    .addTo(map)
                    .bindPopup('<b>' + stop.name + '</b>');
                
                // Push each bus stop marker's position to the stopBounds array
                stopBounds.push(stopMarker.getLatLng());
            });

            return stopBounds;  // Return the bounds of all stop markers
        }

        // Function to fetch and update map data (buses and stops)
        function fetchAndUpdateMap() {
            // Fetch bus data
            $.get("{{ route('tracking.buses') }}", function(buses) {
                console.log("Buses data: ", buses);  // Log the buses data to check
                
                // Clear existing bus markers
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                // Add new bus markers and get bounds
                var busBounds = addBusMarkers(buses, map);

                // Fetch and add bus stop markers
                $.get("{{ route('tracking.stops') }}", function(stops) {
                    console.log("Stops data: ", stops);  // Log the stops data to check
                    var stopBounds = addBusStops(stops, map);

                    // Combine bounds of buses and stops
                    var allBounds = busBounds.concat(stopBounds);

                    // Fit the map to the bounds of all markers with padding to zoom out
                    if (allBounds.length > 0) {
                        var latLngBounds = L.latLngBounds(allBounds);
                        // Apply padding to zoom out
                        map.fitBounds(latLngBounds, {
                            padding: [50, 50]  // Adjust the padding for more zoom out effect
                        });
                    }
                });
            });
        }

        // Initial data load
        fetchAndUpdateMap();

        // Update the map every 10 seconds (real-time data)
        setInterval(fetchAndUpdateMap, 5000);  // Update every 10 seconds
    });
</script>

<script>
    function fetchEstimasi() {
        $.ajax({
            url: "{{ route('get_estimasi_penumpang') }}",
            method: 'GET',
            success: function(response) {
                // Update button text dengan data dari server
                $('#busButton').text('Bus : ' + (response.nama_bus ?? '-'));
                $('#estimasiButton').text('Estimasi : ' + (response.estimasi ?? '-') + ' menit');
            },
            error: function(xhr) {
                console.error('Error fetching estimasi:', xhr);
            }
        });
    }

    // Jalankan fetchEstimasi setiap beberapa detik
    setInterval(fetchEstimasi, 5000); // 5000 ms = 5 detik
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

<script>
@if(session('success'))
Swal.fire({
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    icon: 'success',
    confirmButtonText: 'OK'
});
@endif
</script>
@endsection
