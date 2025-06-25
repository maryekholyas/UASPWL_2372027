<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $apiUrl = 'http://localhost:5001/api';

    public function admin(Request $request)
    {
        if (!Session::has('token') || !Session::has('user')) {
            return redirect()->route('login');
        }

        $token = Session::get('token');
        $user  = Session::get('user');

        if ($user['role'] !== 'administrator') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        try {
            $responseStats = Http::withToken($token)
                ->get($this->apiUrl . '/dashboard/admin');
            $data = $responseStats->json();
            $responseStaff = Http::withToken($token)
                ->get($this->apiUrl . '/dashboard/admin/staff');
            $staffData = $responseStaff->json();
            $staff = $staffData['staff'] ?? [];

            return view('admin.dashboard.index', [
                'user'  => $user,
                'data'  => $data,
                'staff' => $staff,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function addStaff(Request $request)
    {
        if (!Session::has('token') || !Session::has('user')) {
            return redirect()->route('login');
        }

        $token = Session::get('token');
        $user = Session::get('user');

        if ($user['role'] !== 'administrator') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        // Validasi input dari form
        $validated = $request->validate([
            'nama'      => 'required|string',
            'email'     => 'required|email',
            'password'  => 'required|string',
            'role'      => 'required|in:panitia,tim_keuangan'
        ]);

        try {
            // Panggil API backend menggunakan URL yang diinginkan
            $response = Http::withToken($token)
                ->post('http://localhost:5001/api/dashboard/admin/staff', $validated);

            $data = $response->json();
            
            return redirect()->route('dashboard.admin')
                ->with('success', 'Staff berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan staff: ' . $e->getMessage());
        }
    }

    public function allStaff(Request $request)
    {
        if (!Session::has('token') || !Session::has('user')) {
            return redirect()->route('login');
        }

        $token = Session::get('token');
        $user = Session::get('user');

        if ($user['role'] !== 'administrator') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        try {
            // Memanggil API backend untuk mendapatkan data seluruh staff 
            // Pastikan API backend memiliki endpoint GET /admin/staff yang mengembalikan data staff.
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/admin/staff');
            
            $data = $response->json();
            $staff = $data['staff'] ?? [];

            return view('admin.staff.index', [
                'user'  => $user,
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil data staff: ' . $e->getMessage());
        }
    }

    public function updateStaff(Request $request, $id)
    {
        if (!Session::has('token') || !Session::has('user')) {
            return redirect()->route('login');
        }

        $token = Session::get('token');
        $user  = Session::get('user');

        if ($user['role'] !== 'administrator') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama'      => 'required|string',
            'email'     => 'required|email',
            'password'  => 'nullable|string',
            'role'      => 'required|in:panitia,tim_keuangan'
        ]);

        try {
            if (!$validated['password']) {
                unset($validated['password']);
            }
            
            $url = $this->apiUrl . '/dashboard/admin/staff/' . $id;
            $response = Http::withToken($token)
                ->put($url, $validated);

            $data = $response->json();
            
            return redirect()->route('dashboard.admin')
                ->with('success', 'Staff berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui staff: ' . $e->getMessage());
        }
    }

    public function deleteStaff(Request $request, $id)
    {
        if (!Session::has('token') || !Session::has('user')) {
            return redirect()->route('login');
        }

        $token = Session::get('token');
        $user  = Session::get('user');

        if ($user['role'] !== 'administrator') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        try {
            $url = $this->apiUrl . '/dashboard/admin/staff/' . $id;
            $response = Http::withToken($token)
                ->delete($url);

            $data = $response->json();
            
            return redirect()->route('dashboard.admin')
                ->with('success', 'Staff berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus staff: ' . $e->getMessage());
        }
    }

}