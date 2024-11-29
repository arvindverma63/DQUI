<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrController extends Controller
{
    public function qrView()
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($baseUrl . '/qr/' . $restaurantId);

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
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post($baseUrl . '/qr/create', [
            'tableNo' => $request->input('tableNumber'),
            'restaurantId' => $restaurantId,
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
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseUrl = env('API_BASE_URL');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer'.$token,
        ])->delete($baseUrl.'/qr/delete/'.$id);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'QR deleted Successfully');
        } else {
            // Log the error for debugging
            Log::error('QR Generation Failed', ['response' => $response->body()]);

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
