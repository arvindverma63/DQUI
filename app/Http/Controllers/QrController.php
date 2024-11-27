<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrController extends Controller
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
    public function qrView()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/qr/' . $this->restaurantId);

        if ($response->successful()) {
            return view('qr', ['data' => $response->json()]);
        } else {
            // Log the error for debugging
            Log::error('Failed to fetch QR data', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return view('qr', ['data' => []])->with(['error' => 'Unable to fetch QR data']);
        }
    }



    public function addQr(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->post($this->baseUrl . '/qr/create', [
            'tableNo' => $request->input('tableNumber'),
            'restaurantId' => $this->restaurantId,
        ]);


        if ($response->successful()) {
            return redirect()->back()->with('success', 'QR Generated Successfully');
        } else {
            // Log the error for debugging
            Log::error('QR Generation Failed', ['response' => $response->body()]);

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function deleteQr($id){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer'.$this->token,
        ])->delete($this->baseUrl.'/qr/delete/'.$id);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'QR deleted Successfully');
        } else {
            // Log the error for debugging
            Log::error('QR Generation Failed', ['response' => $response->body()]);

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
