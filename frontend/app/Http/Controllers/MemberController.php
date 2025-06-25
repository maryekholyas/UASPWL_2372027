<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $user = Session::get('user');

        if (!$token || !$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $response = Http::withToken($token)
            ->get('http://localhost:5001/api/dashboard/member/my-registrations');

        $registrations = $response->successful() ? $response->json()['registrations'] ?? [] : [];
        $data = [
            'registered_events' => count($registrations)
        ];

        return view('member.dashboard.index', [
            'registrations' => $registrations,
            'data' => $data
            
        ]);
    }


    public function uploadBukti($id, Request $request)
{
    $token = Session::get('token');
    if (!$token) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $request->validate([
        'buktiBayar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Simpan file ke storage/public/bukti-bayar
    $file = $request->file('buktiBayar');
    $path = $file->store('bukti-bayar', 'public');

    // Dapatkan URL file
    $url = asset('storage/' . $path);

    // Kirim ke backend API (pastikan backend menerima URL gambar)
    $response = Http::withToken($token)
        ->post("http://localhost:5001/api/dashboard/member/upload-bukti/{$id}", [
            'buktiBayar' => $url
        ]);

    if ($response->successful()) {
        return back()->with('success', 'Bukti bayar berhasil diupload!');
    } else {
        return back()->with('error', 'Gagal upload bukti bayar.');
    }
}

}