@extends('committee.commitNavigation')
@section('title', 'Scan Absensi')
@section('dashboard-name', 'Scan Absensi')

@section('content')
<style>
    .card-header {
        background-color: #6f42c1;
        color: #fff;
        font-weight: bold;
    }

    .btn-purple {
        background-color: #6f42c1;
        color: #fff;
        border: none;
    }

    .btn-purple:hover {
        background-color: #5a32a3;
        color: #fff;
    }

    .btn-outline-purple {
        border-color: #6f42c1;
        color: #6f42c1;
    }

    .btn-outline-purple:hover {
        background-color: #6f42c1;
        color: #fff;
    }

    #qrPreview {
        max-width: 100%;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 5px;
        background-color: #f8f9fa;
    }

    .section-title {
        color: #6f42c1;
        font-weight: bold;
    }
</style>

<div class="container">
    <h3 class="mb-4 section-title">📷 Scan QR Absensi Peserta</h3>

    {{-- SCAN VIA KAMERA --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Scan QR dengan Kamera</div>
        <div class="card-body">
            <div id="reader" style="width: 100%; max-width: 360px;"></div>
            <form id="scanForm" action="{{ route('committee.event.scanAttendance') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="kodeAbsensi" id="kodeAbsensiCamera">
                <button type="submit" class="btn btn-purple" id="submitCamera" disabled>Submit</button>
            </form>
        </div>
    </div>

    {{-- SCAN VIA UPLOAD GAMBAR --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Scan QR dari Gambar</div>
        <div class="card-body">
            <label for="qrImageInput" class="form-label">Pilih file gambar QR:</label>
            <input type="file" id="qrImageInput" accept="image/*" class="form-control mb-2">

            <div class="mb-2">
                <img id="qrPreview" src="" alt="Preview QR" style="display: none;" />
            </div>

            <button type="button" id="scanImageBtn" class="btn btn-outline-purple mb-2" disabled>Scan Gambar</button>

            <div id="qrResult" class="mt-2 text-success fw-bold"></div>

            <form id="uploadForm" action="{{ route('committee.event.scanAttendance') }}" method="POST">
                @csrf
                <input type="hidden" name="kodeAbsensi" id="kodeAbsensiImage">
                <button type="submit" class="btn btn-purple" id="submitImage" disabled>Submit Absensi</button>
            </form>
        </div>
    </div>

    {{-- INPUT MANUAL --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Input Manual Kode & Generate QR</div>
        <div class="card-body">
            <form id="manualForm" action="{{ route('committee.event.scanAttendance') }}" method="POST">
                @csrf
                <div class="input-group mb-2">
                    <input type="text" name="kodeAbsensi" id="kodeManual" class="form-control" placeholder="Masukkan kode absensi" required>
                    <button type="button" class="btn btn-outline-purple" id="generateQRBtn">Generate QR</button>
                </div>
                <button type="submit" class="btn btn-secondary">Submit Absensi</button>
            </form>
            <div id="qrcode" class="mt-3"></div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<!-- HTML5 QR Code (kamera) -->
<script src="https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js"></script>
<!-- jsQR (untuk decode canvas) -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<!-- QRCode Generator -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // === SCAN VIA KAMERA ===
    let lastCameraResult = null;
    const onCameraScanSuccess = decodedText => {
        if (decodedText !== lastCameraResult) {
            lastCameraResult = decodedText;
            document.getElementById('kodeAbsensiCamera').value = decodedText;
            document.getElementById('submitCamera').disabled = false;
        }
    };
    const cameraScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 }, false);
    cameraScanner.render(onCameraScanSuccess);

    // === SCAN VIA UPLOAD GAMBAR ===
    const fileInput = document.getElementById('qrImageInput');
    const previewImg = document.getElementById('qrPreview');
    const scanBtn = document.getElementById('scanImageBtn');
    const resultDiv = document.getElementById('qrResult');
    const hiddenInput = document.getElementById('kodeAbsensiImage');
    const submitBtn = document.getElementById('submitImage');

    fileInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = () => {
            previewImg.src = reader.result;
            previewImg.style.display = 'block';
            resultDiv.textContent = '';
            hiddenInput.value = '';
            submitBtn.disabled = true;
            scanBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    });

    scanBtn.addEventListener('click', () => {
        const canvas = document.createElement('canvas');
        canvas.width = previewImg.naturalWidth;
        canvas.height = previewImg.naturalHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(previewImg, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);

        if (code) {
            resultDiv.textContent = 'Hasil QR: ' + code.data;
            hiddenInput.value = code.data;
            submitBtn.disabled = false;
        } else {
            alert('QR code tidak terdeteksi pada gambar.');
            resultDiv.textContent = '';
            hiddenInput.value = '';
            submitBtn.disabled = true;
        }
        scanBtn.disabled = true;
    });

    // === GENERATE QR CODE MANUAL ===
    document.getElementById('generateQRBtn').addEventListener('click', () => {
        const kode = document.getElementById('kodeManual').value.trim();
        const qrDiv = document.getElementById('qrcode');
        qrDiv.innerHTML = '';
        if (kode) {
            new QRCode(qrDiv, {
                text: kode,
                width: 200,
                height: 200
            });
        }
    });
});
</script>
@endpush
