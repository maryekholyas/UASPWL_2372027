@extends('committee.commitNavigation')

@section('title', 'Panitia Dashboard')
@section('dashboard-name', 'Panitia Dashboard')

@section('content')

<style>
    .dashboard-title {
        color: #6f42c1;
        font-weight: 600;
    }

    .table thead {
        background-color: #f1e7fd;
        color: #5e3c9b;
    }

    .btn-upload {
        background-color: #6f42c1;
        border: none;
        font-weight: 500;
        color: white;
    }

    .btn-upload:hover {
        background-color: #59339f;
    }

    .btn-view {
        background-color: #5bc0de;
        border: none;
        font-weight: 500;
        color: white;
    }

    .btn-view:hover {
        background-color: #399dbf;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .file-upload-sm {
        max-width: 200px;
        display: inline-block;
    }
</style>

<div class="container my-4">
    <h3 class="dashboard-title">📋 Daftar Event</h3>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    @if(!empty($events) && count($events))
        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td><strong>{{ $event['judul'] }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($event['tanggal'])->format('d/m/Y') }}</td>
                            <td>{{ $event['lokasi'] }}</td>
                            <td>
                                @if(!empty($event['pendaftar']))
                                    <ul class="mb-0 list-unstyled">
                                        @foreach($event['pendaftar'] as $participant)
                                            <li class="mb-2">
                                                <span>{{ $participant['nama'] ?? '-' }} ({{ $participant['email'] ?? '-' }})</span>
                                                @if(isset($participant['hadir']) && $participant['hadir'] == true)
                                                    @if(empty($participant['sertifikat']))
                                                        <form action="{{ route('committee.event.uploadCertificate') }}" method="POST" enctype="multipart/form-data" class="d-inline ms-2">
                                                            @csrf
                                                            <input type="hidden" name="eventId" value="{{ $event['_id'] }}">
                                                            <input type="hidden" name="participantId" value="{{ $participant['_id'] }}">
                                                            <input type="file" name="certificate" class="form-control form-control-sm file-upload-sm" accept="image/*,application/pdf" required>
                                                            <button type="submit" class="btn btn-sm btn-upload mt-1">
                                                                Upload Sertifikat
                                                            </button>
                                                        </form>
                                                    @else
                                                        <a href="{{ $participant['sertifikat'] }}" target="_blank" class="btn btn-sm btn-view ms-2 mt-1">
                                                            Lihat Sertifikat
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="badge badge-warning ms-2">Belum absen</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Belum ada peserta</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info mt-4">Belum ada event ditemukan.</div>
    @endif
</div>
@endsection
