<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;  // Ensure this is imported
use Illuminate\Support\Facades\Cache;

class InventoryController extends Controller
{
    public function addInventory(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'itemName' => 'required|string|max:250',
        'quantity' => 'required|numeric|regex:/^\d+(\.\d{1,3})?$/', // Ensure quantity supports up to 3 decimal places
        'supplierId' => 'integer|required',
        'unit' => 'required|string|max:10',
    ]);

    // Get token and restaurant ID from cache
    $token = Cache::get('token');
    $restaurantId = Cache::get('restaurant_id'); // Assuming restaurantId is stored in the cache
    $appUrl = env('API_BASE_URL');

    // Ensure the token and restaurantId are set
    if (!$token || !$restaurantId) {
        return redirect()->route('login')->withErrors(['error' => 'Authentication required']);
    }

    // Make the POST request to the external API
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->post($appUrl . '/inventories', [
        'itemName' => $validated['itemName'],
        'quantity' => $validated['quantity'],
        'supplierId' => $validated['supplierId'],
        'unit' => $validated['unit'],
        'restaurantId' => $restaurantId, // Add restaurant ID in the request body
    ]);

    // Check for successful response from the API
    if ($response->successful()) {
        return redirect()->back()->with('success', 'Inventory item added successfully');
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to add inventory item'])->withInput();
    }
}

public function deleteStock($id)
{
    $token = Cache::get('token');
    $restaurantId = Cache::get('restaurant_id');
    $appUrl = env('API_BASE_URL');

    // Make API call to delete supplier
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Add space after 'Bearer'
    ])->delete($appUrl . '/inventories/' . $id);

    // Check if the API call was successful
    if ($response->successful()) {
        return redirect()->back()->with(['success' => 'Supplier deleted successfully']);
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to delete supplier'])->withInput();
    }
}

public function getStock()
{
    $token = Cache::get('token');
    $restaurantId = Cache::get('restaurant_id');
    $appUrl = env('API_BASE_URL');  // Ensure you have the base URL defined in your .env file

    // Ensure token exists
    if (!$token) {
        return redirect()->route('login')->withErrors(['message' => 'Token not found. Please log in again.']);
    }

    // Make an API call to get inventories using the restaurantId
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Proper spacing in the Authorization header
    ])->get($appUrl . '/inventories', [
        'restaurantId' => $restaurantId,  // Pass the restaurantId as a query parameter
    ]);

    // Check if the API response is successful
    if ($response->successful()) {
        $stock = $response->json('data');  // Extract the 'data' key from the response
        return response()->json(['data' => $stock, 'message' => 'Stock retrieved successfully'], 200);
    } else {
        // Handle error responses
        return response()->json(['error' => 'Failed to retrieve stock.'], 500);
    }
}

}
