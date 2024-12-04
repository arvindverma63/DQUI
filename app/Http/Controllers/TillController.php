<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TillController extends Controller
{
    public function selectTable(){
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($baseUrl . '/qr/' . $restaurantId);

        if ($response->successful()) {
            return view('components.tables.selectTable', ['data' => $response->json()]);
        } else {
            // Log the error for debugging
            Log::error('Failed to fetch table data', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return view('components.tables.selectTable', ['data' => []])->with(['error' => 'Unable to fetch QR data']);
        }

    }
}
