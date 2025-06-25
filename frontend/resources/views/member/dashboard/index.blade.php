@extends('member.memberNavigation')

@section('title', 'Member Dashboard')
@section('nav-color', 'bg-warning')
@section('dashboard-name', 'Member Dashboard')

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom">
                <h4 class="fw-bold text-primary">📋 My Events</h4>
            </div>
            <div class="card-body bg-light">
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary shadow-sm rounded-3">
                            <div class="card-body">
                                <h5 class="fw-light">Total Event Terdaftar</h5>
                                <p class="h1 fw-semibold">{{ $data['registered_events'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Registrasi --}}
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="fw-semibold mb-3">🗂️ Daftar Event yang Anda Ikuti</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Kode Pembayaran</th>
                                        <th>Kode Absensi</th>
                                        <th>QR Code</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrations as $reg)
                                        <tr>
                                            <td>{{ $reg['event']['judul'] ?? '-' }}</td>
                                            <td>
                                                {{ !empty($reg['event']['tanggal']) 
                                                    ? \Carbon\Carbon::parse($reg['event']['tanggal'])->format('d M Y') 
                                                    : '-' }}
                                            </td>
                                            <td>{{ $reg['event']['lokasi'] ?? '-' }}</td>
                                            <td>
                                                @if($reg['status'] === 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($reg['status'] === 'waiting_approval')
                                                    <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($reg['status']) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $reg['kodePembayaran'] ?? '-' }}</td>
                                            <td>
                                                @if(!empty($reg['kodeAbsensi']))
                                                    <span class="badge bg-primary-subtle text-primary fw-bold">
                                                        {{ $reg['kodeAbsensi'] }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($reg['kodeAbsensi']))
                                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $reg['_id'] }}">
                                                        Lihat QR
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                @if($reg['status'] === 'pending')
                                                    <form action="{{ route('member.uploadBukti', ['id' => $reg['_id']]) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                                                        @csrf
                                                        <input type="file" name="buktiBayar" class="form-control form-control-sm" accept="image/*" required>
                                                        <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                                    </form>
                                                @endif

                                                @if(!empty($reg['buktiBayar']))
                                                    <div class="mt-2">
                                                        <a href="{{ $reg['buktiBayar'] }}" target="_blank">
                                                            <img src="{{ $reg['buktiBayar'] }}" alt="Bukti Bayar" class="img-thumbnail" style="max-width: 80px;">
                                                        </a>
                                                    </div>
                                                @endif

                                                @if($reg['status'] === 'paid' && !empty($reg['sertifikat']))
                                                    <div class="mt-2">
                                                        <a href="{{ $reg['sertifikat'] }}" target="_blank" class="btn btn-sm btn-success w-100">
                                                            🎓 Lihat Sertifikat
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Modal QR Code --}}
                                        @if(!empty($reg['kodeAbsensi']))
                                            <div class="modal fade" id="qrModal-{{ $reg['_id'] }}" tabindex="-1" aria-labelledby="qrModalLabel-{{ $reg['_id'] }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="qrModalLabel-{{ $reg['_id'] }}">QR Code Absensi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div id="qr-svg-{{ $reg['_id'] }}">
                                                                {!! QrCode::format('svg')->size(200)->generate($reg['kodeAbsensi']) !!}
                                                            </div>
                                                            <div class="mt-3">
                                                                <strong>{{ $reg['kodeAbsensi'] }}</strong>
                                                            </div>
                                                            <button class="btn btn-outline-success mt-3" onclick="downloadQrPng('{{ $reg['_id'] }}')">
                                                                ⬇️ Download QR
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Belum ada registrasi event.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- End Daftar Registrasi --}}
            </div>
        </div>
    </div>
</div>

<script>
function downloadQrPng(id) {
    var svgElement = document.querySelector('#qr-svg-' + id + ' svg');
    if (!svgElement) return;

    var serializer = new XMLSerializer();
    var svgString = serializer.serializeToString(svgElement);

    var img = new Image();
    var svgBlob = new Blob([svgString], {type: "image/svg+xml;charset=utf-8"});
    var url = URL.createObjectURL(svgBlob);

    img.onload = function() {
        var canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        var pngUrl = canvas.toDataURL("image/png");
        var a = document.createElement('a');
        a.href = pngUrl;
        a.download = 'qr-absensi-' + id + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };
    img.src = url;
}
</script>
@endsection
