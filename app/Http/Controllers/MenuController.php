<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class MenuController extends Controller
{
    public function addMenu(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'itemName' => 'required|string|max:255',
            'categoryId' => 'required|integer',
            'price' => 'required|numeric',
            'restaurantId' => 'required|string',
            'itemImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stockItems' => 'sometimes|array',
            'stockItems.*.stockId' => 'required_with:stockItems|integer',
            'stockItems.*.quantity' => 'required_with:stockItems|numeric|min:0.001',
        ]);

        // Logging the validated data to confirm structure
        Log::info('Validated Data:', $validatedData);

        $token = Session::get('token');
        $baseUrl = env('API_BASE_URL');

        // Initialize Guzzle client
        $client = new GuzzleClient();

        // Initialize $menuData as an empty array
        $menuData = [];

        // Add basic fields to $menuData
        $menuData[] = ['name' => 'itemName', 'contents' => $validatedData['itemName']];
        $menuData[] = ['name' => 'categoryId', 'contents' => $validatedData['categoryId']];
        $menuData[] = ['name' => 'price', 'contents' => $validatedData['price']];
        $menuData[] = ['name' => 'restaurantId', 'contents' => $validatedData['restaurantId']];

        // Add stockItems to $menuData
        if (!empty($validatedData['stockItems'])) {
            foreach ($validatedData['stockItems'] as $index => $stockItem) {
                $menuData[] = ['name' => "stockItems[$index][stockId]", 'contents' => $stockItem['stockId']];
                $menuData[] = ['name' => "stockItems[$index][quantity]", 'contents' => $stockItem['quantity']];
            }
        }

        // Check if an image file is provided and add it to $menuData
        if ($request->hasFile('itemImage') && $request->file('itemImage')->isValid()) {
            $menuData[] = [
                'name' => 'itemImage',
                'contents' => fopen($request->file('itemImage')->getRealPath(), 'r'),
                'filename' => $request->file('itemImage')->getClientOriginalName()
            ];
        }

        // Debug log the complete $menuData array
        Log::info('Final Menu Data:', $menuData);

        try {
            // Send the POST request with multipart data using Guzzle directly
            $response = $client->post($baseUrl . '/menu', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ],
                'multipart' => $menuData,
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
                return redirect()->back()->with('success', 'Menu item added successfully with associated stock.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to add menu item.']);
            }
        } catch (RequestException $e) {
            // Log error details for debugging
            Log::error('Request failed:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);

            return redirect()->back()->withErrors(['error' => 'Failed to add menu item and stock: ' . $e->getMessage()]);
        }
    }



    public function getCategories()
    {
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
        ])->get($baseUrl . '/categories?restaurantId=' . $restaurantId); // Fixed query string

        // Check if the API request was successful
        if ($response->successful()) {
            // Extract the 'data' array from the response
            $categories = $response->json('data'); // Only get the 'data' field from the response JSON

            // Pass the categories to the view
            return response()->json(['category' => $categories]);
        } else {
            // Handle the case when the API request fails (e.g., invalid token or API error)
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve categories.']);
        }
    }

    public function updateMenuItem(Request $request, $id)
    {
        // Retrieve token and base URL, with a check for token presence
        $token = Session::get('token');
        $baseURL = env('API_BASE_URL');
        if (!$token) {
            return response()->json(['message' => 'Authorization token not found'], 401);
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'itemName' => 'required|string|max:255',
            'categoryId' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'itemImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data for the PUT request
        $data = [
            'itemName' => $validatedData['itemName'],
            'categoryId' => $validatedData['categoryId'],
            'price' => $validatedData['price'],
        ];

        // Check if an image is being uploaded
        if ($request->hasFile('itemImage')) {
            $filePath = $request->file('itemImage')->getRealPath();
            $fileName = $request->file('itemImage')->getClientOriginalName();

            // Send the PUT request with the attached image
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->attach('itemImage', file_get_contents($filePath), $fileName)
                ->put("{$baseURL}/menu/{$id}", $data);
        } else {
            // Send the PUT request without the image
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->put("{$baseURL}/menu/{$id}", $data);
        }

        // Check if the response was successful
        if ($response->successful()) {
            return redirect()->back()->with(['success' => 'Menu item updated successfully.']);
        } else {
            // Log the error for debugging
            Log::error('Failed to update menu item:', [
                'status' => $response->status(),
                'response' => $response->json(),
                'full_response' => $response->body()
            ]);

            return redirect()->back()->with([
                'error' => 'Failed to update menu item',
                'error' => $response->json()
            ], $response->status());
        }
    }

    public function updateMenuStock(Request $request, $id)
    {
        // This will show all request data submitted
        dd($request->all());

        return response()->json(['data' => $request]);

        // Your logic to handle the request
    }






    // Delete Menu Item
    public function deleteMenuItem($id)
    {
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseURL = env('API_BASE_URL');

        // Send the DELETE request to delete the menu item via API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete($baseURL . '/menu/' . $id);

        // Check if the request was successful
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Menu item deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to delete the menu item.']);
        }
    }

    public function getMenu()
    {
        // Retrieve token and restaurant ID from Session
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $baseURL = env('API_BASE_URL');

        // Validate essential data before proceeding
        if (!$token || !$restaurantId || !$baseURL) {
            return response()->json(['error' => 'Missing required data'], 400);
        }

        try {
            // Send GET request to fetch the menu via API with query parameters
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($baseURL . '/menu', [
                'restaurantId' => $restaurantId, // Query parameter
            ]);

            if ($response->successful()) {
                return response()->json([$response->json()]); // Return the JSON response
            } else {
                return response()->json(['error' => 'Failed to fetch data'], $response->status());
            }
        } catch (\Exception $e) {
            // Catch any exceptions and return an appropriate error response
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
