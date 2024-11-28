<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    // Define class properties to store token, restaurantId, and baseUrl
    protected $token;
    protected $restaurantId;
    protected $baseUrl;

    // Initialize these properties in the constructor
    public function __construct()
    {
        $this->token = Session::get('token');
        $this->restaurantId = Session::get('restaurant_id');
        $this->baseUrl = env('API_BASE_URL');
    }

    /**
     * Add a new customer to the system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCustomer(Request $request)
    {
        // Send request to API with Authorization header and post data
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->baseUrl . '/customer', [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phoneNumber' => $request->input('phoneNumber'),
            'address' => $request->input('address'),
            'restaurantId' => $this->restaurantId
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Customer added successfully');
        } else {
            return response()->json([
                'error' => 'Something went wrong',
                'response' => $response->json()
            ], $response->status());
        }
    }

    /**
     * Get customer information by restaurant ID.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomer()
    {
        // Send a GET request to the API to retrieve customer data
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->baseUrl . '/customer/' . $this->restaurantId);

        // Check if the request was successful
        if ($response->successful()) {
            // Return the parsed JSON data in the response
            return response()->json(['data' => $response->json()]);
        } else {
            // Return an error response with the status code from the API
            return response()->json([
                'error' => 'Unable to retrieve customer data',
                'response' => $response->json()
            ], $response->status());
        }
    }

    public function customerTable()
    {
        try {
            // Send a GET request to the API to retrieve customer data
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->get($this->baseUrl . '/customer/' . $this->restaurantId);

            // Check if the response is successful
            if ($response->successful()) {
                // Pass decoded data to the view
                return view('customers', ['data' => $response->json()]);
            } else {
                // Log the error details
                Log::error('Failed to fetch customer data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                // Show an error message in the view
                return view('customers', ['data' => [], 'error' => 'Failed to fetch customer data.']);
            }
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Customer API Exception', ['message' => $e->getMessage()]);

            // Return an error view with a meaningful message
            return view('customers', [
                'data' => [],
                'error' => 'An error occurred while fetching customer data. Please try again later.',
            ]);
        }
    }
}
