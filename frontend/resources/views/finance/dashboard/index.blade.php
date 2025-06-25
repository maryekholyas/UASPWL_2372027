@extends('finance.financeNavigation')

@section('title', 'Finance Dashboard')
@section('nav-color', 'bg-success')
@section('dashboard-name', 'Finance Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Ringkasan Keuangan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center bg-success text-white rounded">
                <div>
                    <h5 class="mb-1">Total Transactions</h5>
                    <h2 class="fw-bold">{{ $stats['totalPaidParticipants'] ?? 0 }}</h2>
                </div>
                <i class="bi bi-cash-coin display-4"></i>
            </div>
        </div>

        <!-- Tabel Pembayaran -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Daftar Pembayaran User</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama User</th>
                                <th>Event</th>
                                <th>Kode Pembayaran</th>
                                <th>Status</th>
                                <th>Bukti Bayar</th>
                                <th>Kode Absensi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment['user']['nama'] ?? '-' }}</td>
                                <td>{{ $payment['event']['judul'] ?? '-' }}</td>
                                <td><code>{{ $payment['kodePembayaran'] }}</code></td>
                                <td>
                                    @if($payment['status'] === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payment['status'] === 'waiting_approval')
                                        <span class="badge bg-warning text-dark">Menunggu ACC</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment['status']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment['buktiBayar'])
                                        <a href="{{ $payment['buktiBayar'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-danger">Belum Upload</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($payment['kodeAbsensi']))
                                        <span class="badge bg-primary">{{ $payment['kodeAbsensi'] }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($payment['status'] === 'waiting_approval' && $payment['buktiBayar'])
                                        <form method="POST" action="{{ route('finance.updatePaymentStatus') }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="kodePembayaran" value="{{ $payment['kodePembayaran'] }}">
                                            <input type="hidden" name="paymentStatus" value="paid">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check2-circle"></i> ACC
                                            </button>
                                        </form>
                                    @elseif($payment['status'] === 'paid')
                                        <span class="text-success fw-semibold">Sudah ACC</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pembayaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
