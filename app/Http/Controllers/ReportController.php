<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
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

    public function stats(){
        $response = Http::withHeaders([
            'Authorization'=> 'Bearer'.$this->token,
        ])->get($this->baseUrl.'/reports/'.$this->restaurantId);

        return response()->json($response->json());
    }
}
