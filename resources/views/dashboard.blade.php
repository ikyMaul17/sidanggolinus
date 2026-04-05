@extends('layouts.app')

@section('dashboard','active')

@section('content')
<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary" id="greeting"></h5>
            <p class="mb-4">
                Anda memiliki {{ $count_pending }} pemesanan sedang diproses, cek detail untuk informasi lanjut
            </p>

            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img
              src="{{url('sneat/assets/img/illustrations/man-with-laptop-light.png')}}"
              height="140"
              alt="View Badge User"
              data-app-dark-img="illustrations/man-with-laptop-dark.png"
              data-app-light-img="illustrations/man-with-laptop-light.png"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">

  <!-- Transactions -->
  <div class="col-md-6 col-lg-6 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Pemesanan Berjalan</h5>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="transactionID"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
          </button>
          
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">

          @if($data_aktif->isEmpty())
              <center>
                <img src="{{url('waiting.svg')}}" style="width: 320px; height: 320px;" alt="Empty" />
                <h4>Belum ada pemesanan</h4>
            </center>
          @else
              @foreach($data_aktif as $data)
                  <li class="d-flex mb-4 pb-1">
                      <div class="avatar flex-shrink-0 me-3">
                          <img src="{{ url('sneat/assets/img/icons/unicons/chart.png') }}" alt="User" class="rounded" />
                      </div>
                      <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                              <small class="text-muted d-block mb-1">{{ $data->nama_penumpang }}</small>
                              <h6 class="mb-0">{{ $data->halte_penjemputan }} - {{ $data->halte_tujuan }}</h6>
                          </div>
                          <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">{{ date('d-m-Y H:i:s', strtotime($data->created_at)) }}</h6>
                          </div>
                      </div>
                  </li>
              @endforeach
          @endif

        </ul>
      </div>
    </div>
  </div>
  <!--/ Transactions -->

    <!-- Transactions -->
  <div class="col-md-6 col-lg-6 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Pemesanan Selesai</h5>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="transactionID"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
          </button>
          
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
        
          @if($data_selesai->isEmpty())
              <center>
                <img src="{{url('waiting.svg')}}" style="width: 320px; height: 320px;" alt="Empty" />
                <h4>Belum ada pemesanan</h4>
            </center>
          @else
              @foreach($data_selesai as $data)
                  <li class="d-flex mb-4 pb-1">
                      <div class="avatar flex-shrink-0 me-3">
                          <img src="{{ url('sneat/assets/img/icons/unicons/chart.png') }}" alt="User" class="rounded" />
                      </div>
                      <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                              <small class="text-muted d-block mb-1">{{ $data->nama_penumpang }}</small>
                              <h6 class="mb-0">{{ $data->halte_penjemputan }} - {{ $data->halte_tujuan }}</h6>
                          </div>
                          <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">{{ date('d-m-Y H:i:s', strtotime($data->created_at)) }}</h6>
                          </div>
                      </div>
                  </li>
              @endforeach
          @endif
          
        </ul>
      </div>
    </div>
  </div>
  <!--/ Transactions -->
</div>
@endsection

@section('script')
<script>
    // Mendapatkan waktu saat ini dari browser pengguna
    var user = {!! json_encode(Auth::user()->nama) !!};
    var currentTime = new Date();
    var currentHour = currentTime.getHours();

    // Mendefinisikan ucapan berdasarkan waktu lokal
    var greeting;
    if (currentHour < 12) {
        greeting = 'Selamat pagi';
    } else if (currentHour < 18) {
        greeting = 'Selamat siang';
    } else {
        greeting = 'Selamat malam';
    }

    // Memasukkan ucapan ke dalam elemen dengan id 'greeting'
    document.getElementById('greeting').textContent = greeting + ', ' + user;
</script>
@endsection