<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FinanceController extends Controller
{
    protected $apiUrl = 'http://localhost:5001/api/dashboard/finance';

    // Dashboard utama finance
    public function dashboard()
    {
        $token = Session::get('token');
        $user = Session::get('user');

        if (!$token || !$user || $user['role'] !== 'tim_keuangan') {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan.');
        }

        // Ambil statistik keuangan
        $response = Http::withToken($token)->get('http://localhost:5001/api/dashboard/finance/stats');
        $stats = $response->successful() ? $response->json()['stats'] ?? [] : [];

        // Ambil daftar registrasi (getAllRegistrations)
        $regResponse = Http::withToken($token)->get('http://localhost:5001/api/dashboard/finance/registrations');
        $registrations = $regResponse->successful() ? $regResponse->json()['registrations'] ?? [] : [];

        return view('finance.dashboard.index', [
            'user' => $user,
            'stats' => $stats,
            'payments' => $registrations // atau 'registrations' sesuai kebutuhan view
        ]);
    }

    // Update status pembayaran peserta
    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'kodePembayaran' => 'required|string',
            'paymentStatus' => 'required|string'
        ]);

        $token = Session::get('token');
        $user = Session::get('user');

        if (!$token || !$user || $user['role'] !== 'tim_keuangan') {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan.');
        }

        $response = Http::withToken($token)->put($this->apiUrl . '/payment-status', [
            'kodePembayaran' => $request->kodePembayaran,
            'paymentStatus' => $request->paymentStatus
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Status pembayaran berhasil diupdate.');
        } else {
            $msg = $response->json('message') ?? 'Gagal update status pembayaran.';
            return back()->with('error', $msg);
        }
    }
}