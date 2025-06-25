@extends('layouts.app')
@section('title', $event['judul'])

@section('content')
<div class="container my-5">
    @if(Session::has('error'))
        <div class="alert alert-danger mb-4 rounded-3">
            {{ Session::get('error') }}
        </div>
    @endif

    @if(Session::has('token'))
        <div class="row align-items-start gy-4">
            <div class="col-md-6">
                <img src="{{ $event['poster'] ?? 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=2224&auto=format&fit=crop' }}" 
                     alt="Foto Event" 
                     class="img-fluid rounded-4 shadow-sm event-img">
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold">{{ $event['judul'] }}</h2>
                <p class="text-muted">{{ $event['deskripsi'] }}</p>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-calendar-event me-2"></i><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event['tanggal'])->format('d M Y') }}</li>
                    <li><i class="bi bi-geo-alt me-2"></i><strong>Lokasi:</strong> {{ $event['lokasi'] }}</li>
                    <li><i class="bi bi-people me-2"></i><strong>Kapasitas:</strong> {{ $event['kapasitas'] }}</li>
                    <li><i class="bi bi-info-circle me-2"></i><strong>Status:</strong> {{ ucfirst($event['status']) }}</li>
                    <li><i class="bi bi-cash-coin me-2"></i><strong>Biaya:</strong> Rp{{ number_format($event['biaya'], 0, ',', '.') }}</li>
                </ul>
                <a href="{{ url('/events') }}" class="btn btn-outline-secondary mt-3 rounded-pill">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Daftar Event
                </a>

                <div class="mt-4">
                    @if(session('user') && session('user.role') === 'member')
                        @if(isset($registrationStatus) && $registrationStatus)
                            <button class="btn btn-outline-secondary rounded-pill" disabled>
                                Status: {{ ucfirst($registrationStatus) }}
                            </button>
                        @else
                            <form method="POST" action="{{ route('member.register', ['eventId' => $event['_id']]) }}">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-pill">Register</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h2 class="fw-bold mb-3">Silakan Login</h2>
            <p class="text-muted">Untuk melihat detail event, silakan login terlebih dahulu.</p>
            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Login</a>
        </div>
    @endif
</div>

{{-- Tambahan Style --}}
<style>
    .event-img {
        max-height: 400px;
        object-fit: cover;
        width: 100%;
    }

    @media (max-width: 768px) {
        .event-img {
            max-height: 250px;
        }
    }

    .btn i {
        vertical-align: middle;
    }
</style>
@endsection
