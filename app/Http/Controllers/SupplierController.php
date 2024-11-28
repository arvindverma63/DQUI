<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;  // Ensure this is imported
use Illuminate\Support\Facades\Session; // Ensure this is imported

class SupplierController extends Controller
{
    public function addSupplier(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'supplierName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phoneNumber' => 'required|string|max:15',
            'rawItem' => 'required|string|max:255',
        ]);

        // Fetch token and restaurantId from the Session
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');

        // Ensure token and restaurantId exist
        if (!$token || !$restaurantId) {
            return response()->json(['error' => 'Authorization token or restaurant ID missing'], 401);
        }

        // Make API call to the external system to create a new supplier
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,  // Corrected Authorization header syntax
        ])->post(env('API_BASE_URL') . '/suppliers', [
            'supplierName' => $validated['supplierName'],
            'email' => $validated['email'],
            'phoneNumber' => $validated['phoneNumber'],
            'rawItem' => $validated['rawItem'],
            'restaurantId' => $restaurantId,  // Sending restaurantId as part of the request payload
        ]);

        // Check if the API request was successful
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Supplier add successfully');
        } else {
            // Handle failure case
            return response()->json(['error' => 'Failed to add supplier', 'details' => $response->json()], $response->status());
        }
    }
    public function deleteSupplier($id)
{
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $appUrl = env('API_BASE_URL');

    // Make API call to delete supplier
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Add space after 'Bearer'
    ])->delete($appUrl . '/suppliers/' . $id);

    // Check if the API call was successful
    if ($response->successful()) {
        return redirect()->back()->with(['success' => 'Supplier deleted successfully']);
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to delete supplier'])->withInput();
    }
}

public function updateSupplier(Request $request, $id)
{
    // Validate the request inputs
    $validated = $request->validate([
        'supplierName' => 'string|max:40',
        'email' => 'email|max:40',
        'rawItem' => 'string|max:255',
        'phoneNumber' => 'string|max:15'
    ]);

    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $appUrl = env('API_BASE_URL');

    // Make the API call to update the supplier
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Add a space after 'Bearer'
    ])->put($appUrl . '/suppliers/' . $id, [ // Add '/' before supplier ID
        'supplierName' => $validated['supplierName'],
        'email' => $validated['email'],
        'phoneNumber' => $validated['phoneNumber'],
        'rawItem' => $validated['rawItem'],
    ]);

    // Check if the API request was successful
    if ($response->successful()) {
        return redirect()->back()->with('success', 'Supplier updated successfully');
    } else {
        return redirect()->back()->with('error', 'Failed to update supplier');
    }
}

public function getSupplier()
{
    // Retrieve the token and restaurant ID from Session
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $appUrl = env('API_BASE_URL');

    // Ensure token and restaurantId are available
    if (!$token || !$restaurantId) {
        return response()->json(['error' => 'Authentication required'], 401);
    }

    // Make an HTTP GET request to fetch the suppliers for the restaurant
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,  // Add a space after Bearer
    ])->get($appUrl . '/suppliers', [
        'restaurantId' => $restaurantId  // Pass the restaurant ID as a query parameter
    ]);

    // Check if the response is successful
    if ($response->successful()) {
        // Return the suppliers data as a JSON response
        return response()->json(['suppliers' => $response->json('data')], 200);
    }

    // Handle failure
    return response()->json(['error' => 'Failed to fetch suppliers'], $response->status());
}


}
