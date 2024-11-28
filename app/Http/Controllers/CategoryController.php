<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        // Validate the incoming request
        $validate = $request->validate([
            'categoryName' => 'string|required',
            'categoryImage' => 'image|required|max:2048', // Optional: Max size 2MB
        ]);

        // Retrieve restaurant ID and token from Session
        $restaurantId = Session::get('restaurant_id');
        $token = Session::get('token'); // Retrieve the bearer token from the Session

        // Check if restaurantId and token exist in Session
        if (!$restaurantId) {
            return response()->json(['error' => 'Restaurant ID not found in Session.'], 404);
        }

        if (!$token) {
            return response()->json(['error' => 'Authorization token not found in Session.'], 401);
        }

        // Set API base URL from environment
        $baseURL = env('API_BASE_URL');

        // Make API request to add category, sending the bearer token in the Authorization header
        // Send image file directly with the request, no local storage needed
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token, // Send the token in the header
        ])->attach('categoryImage', file_get_contents($request->file('categoryImage')), $request->file('categoryImage')->getClientOriginalName())
          ->post($baseURL . '/category', [
              'categoryName' => $validate['categoryName'],
              'restaurantId' => $restaurantId
          ]);

        // Handle API response
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Category added successfully.');
        } else {
            // Log error message for debugging (optional)
            Log::error('Failed to add category', ['response' => $response->json()]);

            return redirect()->back()->withErrors(['error' => 'Failed to add category.']);
        }
    }
    public function editCategory($id, Request $request)
{
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $baseURL = env('API_BASE_URL');

    $data = [
        'categoryName' => $request->categoryName,
        'restaurantId' => $restaurantId
    ];

    // Check if an image is being uploaded
    if ($request->hasFile('categoryImage')) {
        $imagePath = $request->file('categoryImage')->getRealPath();
        $imageName = $request->file('categoryImage')->getClientOriginalName();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->attach('categoryImage', file_get_contents($imagePath), $imageName)
          ->put($baseURL . '/category/' . $id, $data);
    } else {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put($baseURL . '/category/' . $id, $data);
    }

    if ($response->successful()) {
        return redirect()->back()->with('success', 'Category updated successfully.');
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to update the category.']);
    }
}

public function deleteCategory($id)
{
    // Retrieve the token and restaurantId from Session
    $token = Session::get('token');
    $restaurantId = Session::get('restaurant_id');
    $baseURL = env('API_BASE_URL'); // Ensure correct usage of env()

    // Check if the token exists
    if (!$token) {
        return redirect()->back()->withErrors(['error' => 'Authorization token not found. Please log in again.']);
    }

    // Make the DELETE request to the API
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token, // Correct the space after Bearer
    ])->delete($baseURL . '/category/' . $id);

    // Handle the API response
    if ($response->successful()) {
        return redirect()->back()->with('success', 'Category deleted successfully.');
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to delete the category.']);
    }
}


}
