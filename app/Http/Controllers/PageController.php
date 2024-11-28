<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class PageController extends Controller
{
    public function account(){
        return view('account');
    }
    public function category()
{
    // Retrieve the token and restaurantId from the Session
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');

    // Check if token is available
    if (!$token) {
        return redirect()->route('login')->withErrors(['message' => 'Token not found or expired. Please log in again.']);
    }

    // Fetch API base URL from the environment configuration
    $baseUrl = env('API_BASE_URL');

    // Make the GET request to fetch categories with correct query string
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get($baseUrl . '/categories?restaurantId=' . $restaurantId);

    // Check if the API request was successful
    if ($response->successful()) {
        // Extract the 'data' array from the response
        $categories = $response->json('data');

        // Set up pagination manually
        $perPage = 10; // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Get the current page
        $currentItems = array_slice($categories, ($currentPage - 1) * $perPage, $perPage); // Slice the array to get items for the current page
        $paginatedCategories = new LengthAwarePaginator(
            $currentItems,
            count($categories), // Total number of items
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()] // Path for pagination links
        );

        // Pass the paginated categories to the view
        return view('category', ['categories' => $paginatedCategories]);
    } else {
        // Handle the case when the API request fails (e.g., invalid token or API error)
        return redirect()->back()->withErrors(['error' => 'Failed to retrieve categories.']);
    }
}



public function menu()
{
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');

    // Check if token is available
    if (!$token) {
        return redirect()->route('login')->withErrors(['message' => 'Token not found or expired. Please log in again.']);
    }

    // Fetch API base URL from the environment configuration
    $baseUrl = env('API_BASE_URL');

    // Make the GET request to fetch menu items
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get($baseUrl . '/menu?restaurantId=' . $restaurantId);

    // Check if the API request was successful
    if ($response->successful()) {
        // Extract the 'data' array from the response
        $data = $response->json('data');

        // Check if 'menus' and 'inventoryOptions' exist in the response data
        $menus = isset($data['menus']) ? $data['menus'] : [];
        $inventoryOptions = isset($data['inventoryOptions']) ? $data['inventoryOptions'] : [];

        // Pass menus and inventory options directly to the view without pagination
        return view('menu', [
            'menuItems' => [
                'data' => [
                    'menus' => $menus,  // Directly passing the menus array
                    'inventoryOptions' => $inventoryOptions
                ],
                'message' => 'Menus and inventory options retrieved successfully.'
            ]
        ]);
    } else {
        // Handle the case when the API request fails
        return redirect()->back()->withErrors(['error' => 'Failed to retrieve menu items.']);
    }
}




    public function order(){
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
    public function notification(){
        return view('notification');
    }
    public function setting(){
        return view('setting');
    }
    public function login(){
        return view('login');
    }
    public function verifyOtp(){
        return view('verify-otp');
    }
    public function supplier()
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $appUrl = env('API_BASE_URL');  // Ensure you have the base URL defined in your .env file

        // Ensure token exists
        if (!$token) {
            return redirect()->route('login')->withErrors(['message' => 'Token not found. Please log in again.']);
        }

        // Make an API call to get suppliers using the restaurantId
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,  // Proper spacing in the Authorization header
        ])->get($appUrl . '/suppliers', [
            'restaurantId' => $restaurantId,  // Pass the restaurantId as a query parameter
        ]);

        // Check if the API response is successful
        if ($response->successful()) {
            $suppliers = $response->json('data');  // Extract the 'data' key from the response
            return view('supplier', ['suppliers' => $suppliers]);  // Pass the supplier data to the view
        } else {
            // Handle error responses
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve suppliers.']);
        }
    }

    public function stock(){
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $appUrl = env('API_BASE_URL');  // Ensure you have the base URL defined in your .env file

        // Ensure token exists
        if (!$token) {
            return redirect()->route('login')->withErrors(['message' => 'Token not found. Please log in again.']);
        }

        // Make an API call to get suppliers using the restaurantId
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,  // Proper spacing in the Authorization header
        ])->get($appUrl . '/inventories', [
            'restaurantId' => $restaurantId,  // Pass the restaurantId as a query parameter
        ]);

        // Check if the API response is successful
        if ($response->successful()) {
            $stock = $response->json('data');  // Extract the 'data' key from the response
            return view('stock', ['stocks' => $stock]);  // Pass the supplier data to the view
        } else {
            // Handle error responses
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve suppliers.']);
        }
    }

    public function till(){
        return view('till');
    }

}
