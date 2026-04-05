@extends('layouts_supir.app')

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
    @if($needsInspection)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Cek Kondisi Bus
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row g-3">
    <div class="col-6">
        <a href="{{ route('shuttle') }}" class="text-decoration-none">
            <div class="card border-1 shadow-sm card-menu text-center">
            <i class="fas fa-bus mt-3"></i>
            <p class="mt-2">Shuttle</p>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('antrian') }}" class="text-decoration-none">
            <div class="card border-1 shadow-sm card-menu text-center">
            <i class="fas fa-users mt-3"></i>
            <p class="mt-2">Antrian</p>
            </div>
        </a>
    </div>
    <div class="col-6">
    <a href="{{ route('feedback_supir') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-comments mt-3"></i>
        <p class="mt-2">Umpan Balik</p>
        </div>
    </a>
    </div>
    <div class="col-6">
    <a href="{{ route('setting_bus') }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-cog mt-3"></i>
        <p class="mt-2">Pengaturan</p>
        </div>
    </a>
    </div>

    

    <div class="col-6">
    <a href="{{ route('list_umpan_balik_supir', ['tab' => 'umpan_balik']) }}" class="text-decoration-none">
        <div class="card border-1 shadow-sm card-menu text-center">
        <i class="fas fa-cloud-download mt-3"></i>
        <p class="mt-2">Keluhan Layanan</p>
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

@endsection
