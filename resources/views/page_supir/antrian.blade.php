@extends('layouts_supir.app')

@section('css')
@endsection

@section('content')
<section class="container py-3">
    <div class="text-center">
        <h5>Antrian</h5>
    </div>

    <!-- Daftar Penumpang -->
    <div class="mt-3">
        <table class="table table-striped" style="font-size: 14px;">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Berangkat</th>
                    <th>Tujuan</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($antrian as $data)
                <tr>
                    <td>{{ $data->penumpang->nama }}</td>
                    <td>{{ $data->nama_penjemputan }}</td>
                    <td>{{ $data->nama_tujuan }}</td>
                    <td>{{ $data->estimasi_waktu }} menit</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmationModal">
    Confirmation
</button>
<p></p>
<button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#kendalaModal">
    Kendala
</button>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('submit_halte') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Submit Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dropdown for Halte -->
                    <div class="mb-3">
                        <label for="halte" class="form-label">Halte</label>
                        <select name="id_halte" id="halte" class="form-select" required>
                            <option value="" selected disabled>Pilih Halte</option>
                            @foreach($halte as $data)
                                <option value="{{ $data->id }}" data-lat="{{ $data->latitude }}" data-long="{{ $data->longitude }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Latitude -->
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
                    </div>

                    <!-- Longitude -->
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="kendalaModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('submit_kendala') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="kendalaModalLabel">Submit Kendala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dropdown for Halte -->
                    <!-- Latitude -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Kendala</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
document.addEventListener('DOMContentLoaded', () => {
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;
            },
            (error) => {
                console.error("Error fetching location: ", error);
                alert('Unable to fetch location. Please enable location services.');
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
});
</script> -->

<script>
document.getElementById("halte").addEventListener("change", function() {
    var selectedOption = this.options[this.selectedIndex];
    var lat = selectedOption.getAttribute("data-lat");
    var long = selectedOption.getAttribute("data-long");

    document.getElementById("latitude").value = lat ? lat : '';
    document.getElementById("longitude").value = long ? long : '';
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
@endsection
