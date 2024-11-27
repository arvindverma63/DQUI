<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $token;
    protected $restaurantId;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = Cache::get('token');
        $this->restaurantId = Cache::get('restaurant_id');
        $this->baseUrl = env('API_BASE_URL');
    }

    public function addTransaction()
    {
        // Check if all required data exists
        if (!$this->token || !$this->restaurantId || !$this->baseUrl) {
            // Log the missing data for debugging
            Log::error('Missing configuration data', [
                'token' => $this->token,
                'restaurantId' => $this->restaurantId,
                'baseUrl' => $this->baseUrl,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Missing required configuration data',
            ], 400);
        }

        // Prepare response data
        $data = [
            'success' => true,
            'token' => $this->token,
            'restaurantId' => $this->restaurantId,
            'baseUrl' => $this->baseUrl,
        ];

        return response()->json($data, 200);
    }

    public function getTransactions()
    {
        return view('transactions');
    }
}
