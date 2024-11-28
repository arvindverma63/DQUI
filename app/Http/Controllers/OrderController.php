<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
{
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $app_url = env('API_BASE_URL');

    // Ensure token exists
    if (!$token) {
        return redirect()->route('login')->withErrors(['message' => 'Token not found. Please log in again.']);
    }

    // Make an API call to get orders using the restaurantId
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Fixed the syntax for Authorization header
    ])->get($app_url . '/orders', [
        'restaurantId' => $restaurantId,  // Sending restaurantId as a query parameter
    ]);

    // Check if the API response is successful
    if ($response->successful()) {
        $orders = $response->json('data');  // Get the 'data' array from the response
        return view('order', ['orders' => $orders]);
    } else {
        // Handle error responses
        return redirect()->back()->withErrors(['error' => 'Failed to retrieve orders.']);
    }
}

public function updateStatus(Request $request, $id)
{
    // Validate the incoming request with the correct statuses
    $request->validate([
        'status' => 'required|in:processing,accept,reject,complete',
    ]);

    // Get token and restaurant ID from Session
    $token = Session::get('token');
    $status = $request->input('status');  // Get the new status from the request
    $app_url = env('API_BASE_URL');

    // Make sure the token exists
    if (!$token) {
        return redirect()->route('login')->withErrors(['message' => 'Token not found. Please log in again.']);
    }

    // Make an API call to update the order status
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->put($app_url . '/orders/' . $id . '/status', [
        'status' => $status,  // Send restaurantId with the request
    ]);

    // Check if the API response is successful
    if ($response->successful()) {
        return redirect()->back()->with('success', 'Order status updated successfully');
    } else {
        // Handle the failure case
        return redirect()->back()->withErrors(['error' => 'Failed to update order status.']);
    }
}

public function getNotification(){
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $app_url = env('API_BASE_URL');
    $response = Http::withHeaders([
        'Authorization'=>'Bearer'.$token,
    ])->get($app_url.'/notification/'.$restaurantId);

    if($response){
        return response()->json($response);
    }
}



}
