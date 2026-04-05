@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Daftar</span> Halte Pergi</h4>

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

    <button type="button" class="btn btn-primary" style="margin:20px;" data-bs-toggle="modal" data-bs-target="#basicModal">
        <span class="tf-icons bx bx-plus"></span>&nbsp; Tambah Data
    </button>

    <table id="myTable" class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data as $raw)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $raw->nama }}</td>
                <td>{{ $raw->latitude }}</td>
                <td>{{ $raw->longitude }}</td>
                <td>{{ $raw->keterangan }}</td>
                <td>
                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $raw->id }}" >
                        <span class="tf-icons bx bx-edit"></span>
                    </button>

                    <a href="{{ url('halte_pergi/delete', $raw->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                        <span class="tf-icons bx bx-trash"></span>
                    </a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal{{ $raw->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Edit Halte Pergi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ url('halte_pergi/update', $raw->id)}}" method="post">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $raw->nama }}" required />
                        </div>

                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control" id="latEdit{{ $raw->id }}" value="{{ $raw->latitude }}" required />
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control" id="lngEdit{{ $raw->id }}" value="{{ $raw->longitude }}" required />
                        </div>

                        <small>Bisa langsung isi lat long atau drag lewat map</small>
                        <div id="mapEdit{{ $raw->id }}" style="height: 300px; margin-bottom: 20px;"></div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control">{{ $raw->keterangan }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            <!-- End Modal Edit -->

            @endforeach
        </tbody>
      </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Tambah Halte Pergi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="{{ url('halte_pergi/store')}}" method="post">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" id="latInput" required />
            </div>

            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" id="lngInput" required />
            </div>

            <small>Bisa langsung isi lat long atau drag lewat map</small>
            <div id="map" style="height: 300px; margin-bottom: 20px;"></div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        </div>
    </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    
    function setupMap(mapId, latId, lngId, initialLat, initialLng) {
    const map = L.map(mapId).setView([initialLat, initialLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    const marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);
    
    // Update input fields when marker is dragged
    marker.on('dragend', function(e) {
        const latlng = e.target.getLatLng();
        document.getElementById(latId).value = latlng.lat;
        document.getElementById(lngId).value = latlng.lng;
    });

    // Update marker position when lat/lng inputs change
        document.getElementById(latId).addEventListener('input', function() {
            const newLat = parseFloat(this.value);
            const newLng = parseFloat(document.getElementById(lngId).value);
            if (!isNaN(newLat) && !isNaN(newLng)) {
                marker.setLatLng([newLat, newLng]);
                map.setView([newLat, newLng]);
            }
        });

        document.getElementById(lngId).addEventListener('input', function() {
            const newLat = parseFloat(document.getElementById(latId).value);
            const newLng = parseFloat(this.value);
            if (!isNaN(newLat) && !isNaN(newLng)) {
                marker.setLatLng([newLat, newLng]);
                map.setView([newLat, newLng]);
            }
        });

        return { map, marker };
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;

                // Initialize the "Tambah Data" map with the current location
                const tambahMap = setupMap('map', 'latInput', 'lngInput', currentLat, currentLng);

                // Initialize maps for each "Edit" modal and store them in a dictionary
                const editMaps = {};
                @foreach($data as $raw)
                editMaps[{{ $raw->id }}] = setupMap(
                    `mapEdit{{ $raw->id }}`,
                    `latEdit{{ $raw->id }}`,
                    `lngEdit{{ $raw->id }}`,
                    {{ $raw->latitude }},
                    {{ $raw->longitude }}
                );
                @endforeach

                // Ensure map resizes properly when modals are shown
                $('#basicModal').on('shown.bs.modal', function() {
                    tambahMap.map.invalidateSize();
                });

                @foreach($data as $raw)
                $(`#editModal{{ $raw->id }}`).on('shown.bs.modal', function() {
                    editMaps[{{ $raw->id }}].map.invalidateSize();
                });
                @endforeach
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });

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
