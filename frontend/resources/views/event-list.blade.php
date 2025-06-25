@extends('layouts.app')
@section('title', 'Daftar Event')

@section('content')
<h1 class="mb-4 fw-bold text-center">Daftar Event</h1>

@if(count($events))
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($events as $event)
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4 event-card">
                    <img src="{{ $event['poster'] ?? 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=2224&auto=format&fit=crop' }}" 
                         class="card-img-top rounded-top-4 event-img" alt="Foto Event">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">{{ $event['judul'] }}</h5>
                        <p class="card-text small">
                            <i class="bi bi-calendar-event me-2"></i><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event['tanggal'])->format('d M Y') }}<br>
                            <i class="bi bi-geo-alt me-2"></i><strong>Lokasi:</strong> {{ $event['lokasi'] }}<br>
                            <i class="bi bi-info-circle me-2"></i><strong>Status:</strong> {{ ucfirst($event['status']) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center mb-2 text-muted small">
                            <span><i class="bi bi-people me-1"></i>{{ $event['kapasitas'] }} orang</span>
                            <span><i class="bi bi-cash me-1"></i>Rp{{ number_format($event['biaya'], 0, ',', '.') }}</span>
                        </div>
                        <div>
                            @if(Session::has('token'))
                                <a href="{{ route('events.show', $event['_id']) }}" class="btn btn-outline-primary w-100 rounded-pill">Lihat Detail</a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-outline-primary w-100 rounded-pill"
                                   onclick="return confirm('Silakan login terlebih dahulu untuk melihat detail event.')">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted text-center">Tidak ada event tersedia saat ini.</p>
@endif

{{-- Tambahan Style --}}
<style>
    .event-img {
        height: 200px;
        object-fit: cover;
    }

    .event-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .event-card:hover {
        transform: scale(1.015);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
        .event-img {
            height: 160px;
        }
    }
</style>
@endsection
