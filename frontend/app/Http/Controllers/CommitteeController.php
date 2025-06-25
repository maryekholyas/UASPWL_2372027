<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CommitteeController extends Controller
{
    /** Base URL untuk backend API */
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url', 'http://localhost:5001/api');
    }

    /**
     * Tampilkan halaman dashboard overview panitia
     */
    public function committee()
    {
        $this->authorizePanitia();

        $response = Http::withToken(Session::get('token'))
            ->get("{$this->apiUrl}/dashboard/event");

        $events = $response->successful()
            ? $response->json('events', [])
            : [];

        return view('committee.dashboard.index', compact('events'));
    }

    /**
     * Tampilkan form buat event & daftar event
     */
    public function indexEvent()
    {
        $this->authorizePanitia();

        $response = Http::withToken(Session::get('token'))
            ->get("{$this->apiUrl}/dashboard/event");

        $events = $response->successful()
            ? $response->json('events', [])
            : [];

        return view('committee.dashboard.create', compact('events'));
    }

    /**
     * Proses penyimpanan event baru
     */
    public function storeEvent(Request $request)
    {
        $this->authorizePanitia();

        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal'   => 'required|date',
            'lokasi'    => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'durasi'    => 'required|numeric',
            'biaya'     => 'required|numeric',
            'sesi'      => 'nullable|string',
            'poster'    => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster'] = asset("storage/{$path}");
        }

        $response = Http::withToken(Session::get('token'))
            ->post("{$this->apiUrl}/dashboard/event", $validated);

        if ($response->successful()) {
            return redirect()
                ->route('committee.event.index')
                ->with('success', 'Event berhasil ditambahkan');
        }

        return back()
            ->withInput()
            ->with('error', 'Gagal menambahkan event: ' . $response->body());
    }

    /**
     * Proses penghapusan event
     */
    public function deleteEvent($id)
    {
        $this->authorizePanitia();

        $response = Http::withToken(Session::get('token'))
            ->delete("{$this->apiUrl}/dashboard/event/{$id}");

        if ($response->successful()) {
            return redirect()
                ->route('committee.event.index')
                ->with('success', 'Event berhasil dihapus');
        }

        return back()
            ->with('error', 'Gagal menghapus event: ' . $response->body());
    }

    /**
     * Proses pengunggahan sertifikat
     */
    public function uploadCertificate(Request $request)
    {
        $this->authorizePanitia();

        $request->validate([
            'eventId' => 'required|string',
            'participantId' => 'required|string',
            'certificate' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048'
        ]);

        // Upload file ke storage lokal/public
        $path = $request->file('certificate')->store('sertifikat', 'public');
        $sertifikatLink = asset("storage/{$path}");

        $token = Session::get('token');

        // Kirim link ke backend
        $response = Http::withToken($token)
            ->post("{$this->apiUrl}/dashboard/event/certificate", [
                'eventId' => $request->eventId,
                'participantId' => $request->participantId,
                'sertifikat' => $sertifikatLink,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Sertifikat berhasil diupload');
        }
        return back()->with('error', 'Gagal upload sertifikat: ' . $response->body());
    }

    /**
     * Proses scan kehadiran
     */
    public function scanAttendance(Request $request)
    {
        $this->authorizePanitia();

        $request->validate([
            'kodeAbsensi' => 'required|string'
        ]);

        $token = Session::get('token');
        $response = Http::withToken($token)
            ->post("http://localhost:5001/api/dashboard/event/scan", [
                'kodeAbsensi' => $request->kodeAbsensi
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Check-in berhasil: ' . $response->json('message'));
        }
        return back()->with('error', 'Gagal check-in: ' . $response->body());
    }

    /**
     * Tampilkan form unggah sertifikat
     */

    /**
     * Cek autentikasi & role panitia
     */
    protected function authorizePanitia()
    {
        if (!Session::has('token') || !Session::has('user')) {
            redirect()->route('login')->throwResponse();
        }

        $user = Session::get('user');
        if (($user['role'] ?? '') !== 'panitia') {
            back()->with('error', 'Unauthorized access')->throwResponse();
        }
    }
}
