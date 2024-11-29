<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

    public function stats()
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        $response = Http::get($baseUrl.'/reports/'.$restaurantId);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        // Handle error response
        return response()->json([
            'error' => 'Failed to fetch data',
            'message' => $response->body(),
            'url'=>$restaurantId,
        ], $response->status());
    }
}
