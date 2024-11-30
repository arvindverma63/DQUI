<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{

    public function addTransaction()
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        // Check if all required data exists
        if (!$token || !$restaurantId || !$baseUrl) {
            // Log the missing data for debugging
            Log::error('Missing configuration data', [
                'token' => $token,
                'restaurantId' => $restaurantId,
                'baseUrl' => $baseUrl,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Missing required configuration data',
            ], 400);
        }

        // Prepare response data
        $data = [
            'success' => true,
            'token' => $token,
            'restaurantId' => $restaurantId,
            'baseUrl' => $baseUrl,
        ];

        return response()->json($data, 200);
    }

    public function getTransactions()
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');

        $response = Http::withHeaders([
            'Authorization'=>'Bearer'.$token,
        ])->get($baseUrl.'/transactions/'.$restaurantId);
        return view('transactions',['data'=>$response->json()]);
    }
}
