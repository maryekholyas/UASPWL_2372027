<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EventController extends Controller
{
    protected $apiUrl = 'http://localhost:5001/api';
    protected $events;

    public function __construct()
    {
        $response = Http::get($this->apiUrl . '/events');
        $data = $response->json();
        $this->events = $data['events'] ?? [];
    }

    public function index()
    {
        return view('event-list', ['events' => $this->events]);
    }

    public function show($id)
    {
        $event = collect($this->events)->firstWhere('_id', $id);

        if (!$event) {
            abort(404, 'Event tidak ditemukan');
        }

        $registrationStatus = null;
        $user = Session::get('user');
        $token = Session::get('token');
        if ($user && $user['role'] === 'member') {
            // Ambil status pendaftaran dari API
            $response = Http::withToken($token)
                ->get("http://localhost:5001/api/member/my-registrations");
            if ($response->successful()) {
                $regs = $response->json('registrations') ?? [];
                foreach ($regs as $reg) {
                    if (isset($reg['event']['_id']) && $reg['event']['_id'] == $id) {
                        $registrationStatus = $reg['status'];
                        break;
                    }
                }
            }
        }

        return view('event-detail', [
            'event' => $event,
            'registrationStatus' => $registrationStatus
        ]);
    }

    public function registerEvent(Request $request, $eventId)
    {
        // Pastikan user login dan role member
        $user = Session::get('user');
        $token = Session::get('token');
        if (!$user || $user['role'] !== 'member') {
            return redirect()->back()->with('error', 'Hanya member yang bisa mendaftar event.');
        }

        // Kirim request ke API backend
        $response = Http::withToken($token)
            ->post("http://localhost:5001/api/dashboard/member/register/{$eventId}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berhasil mendaftar event!');
        } else {
            $msg = $response->json('message') ?? 'Gagal mendaftar event.';
            return redirect()->back()->with('error', $msg);
        }
    }
}
