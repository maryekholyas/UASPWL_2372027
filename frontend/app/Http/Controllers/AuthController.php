<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $apiUrl = 'http://localhost:5001/api';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $response = Http::post($this->apiUrl . '/auth/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Session::put('token', $data['token']);
                Session::put('user', $data['user']);

                // Redirect berdasarkan role
                switch ($data['user']['role']) {
                    case 'administrator':
                        return redirect()->route('dashboard.admin');
                    case 'tim_keuangan':
                        return redirect()->route('finance.dashboard');
                    case 'panitia':
                        return redirect()->route('committee.dashboard');
                    case 'member':
                        return redirect()->route('member.dashboard');
                }
            }

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Terjadi kesalahan pada server.',
            ])->onlyInput('email');
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        try {
            $response = Http::post($this->apiUrl . '/auth/register', [
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'member' 
            ]);

            if ($response->successful()) {
                return redirect()->route('login')
                    ->with('success', 'Registrasi berhasil! Silakan login.');
            }

            $errorMessage = $response->json()['message'] ?? 'Gagal melakukan registrasi.';
            return back()
                ->withInput()
                ->withErrors(['email' => $errorMessage]);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Terjadi kesalahan pada server.']);
        }
    }

    public function logout(Request $request)
    {
        Session::forget('token');
        Session::forget('user');
        
        return redirect()->route('login')
            ->with('success', 'Berhasil logout.');
    }
}