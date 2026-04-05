@extends('layouts_penumpang.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection

@section('content')
<!-- Card Menu -->
<section class="container py-3">
    <div class="text-center">
        <h5>Tracking Bus</h5>
    </div>
    <div id="map" style="height: 500px;"></div>
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
                    .bindPopup('<b>' + bus.name + '</b>')
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
@endsection
