<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data dari API Express
        $response = Http::get('http://localhost:5001/api/events');
        $events = $response['events'] ?? [];

        return view('home', compact('events'));
    }
}
