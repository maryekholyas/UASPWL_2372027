@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="mb-4">
    <div id="eventCarousel" class="carousel slide rounded-4 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($events as $index => $event)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="https://images.unsplash.com/photo-1561489396-888724a1543d?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D{{ urlencode($event['judul']) }}"
                         class="d-block w-100 rounded-4 carousel-image" alt="{{ $event['judul'] }}">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>{{ $event['judul'] }}</h5>
                        <p>{{ $event['deskripsi'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Berikutnya</span>
        </button>
    </div>
</div>

<!-- Our Popular Event -->
<div class="text-center mt-5">
    <h4 class="fw-bold mb-4">Our Popular Event</h4>
    <div class="marquee-container">
        <div class="marquee popular-marquee">
            @foreach(collect($events)->sortByDesc('kapasitas')->take(3) as $popular)
                <div class="card shadow-sm rounded-4 border-0 popular-card">
                    <img src="https://images.unsplash.com/photo-1558008258-3256797b43f3?q=80&w=2662&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D{{ urlencode($popular['judul']) }}"
                         class="card-img-top rounded-top-4" alt="{{ $popular['judul'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $popular['judul'] }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($popular['deskripsi'], 50) }}</p>
                        <small class="text-secondary">Kapasitas: {{ $popular['kapasitas'] }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- CSS --}}
<style>
    .carousel-image {
        height: 400px;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .carousel-image {
            height: 250px;
        }
    }

    .marquee-container {
        overflow: hidden;
        width: 100%;
    }

    .marquee {
        display: inline-flex;
        white-space: nowrap;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }

    .sponsor-strip {
        gap: 3rem;
        animation-name: marquee-left;
        animation-duration: 20s;
    }

    .sponsor-strip img {
        height: 50px;
        filter: grayscale(100%);
        transition: filter 0.3s;
    }

    .sponsor-strip img:hover {
        filter: grayscale(0%);
    }

    .popular-marquee {
        animation-name: marquee-right;
        animation-duration: 60s;
        gap: 2rem;
    }

    .popular-card {
        min-width: 280px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .popular-card:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .marquee:hover {
        animation-play-state: paused;
    }

    @keyframes marquee-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }

    @keyframes marquee-right {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endsection
