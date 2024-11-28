<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $validate = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        // Fetch the API base URL from the environment configuration (.env)
        $API_BASE_URL = env('API_BASE_URL');

        // Make the POST request to the external API for authentication
        $response = Http::post($API_BASE_URL . "/login", [
            'email' => $validate['email'],
            'password' => $validate['password']
        ]);

        // Debugging: Log the API response
        Log::info('Login API Response: ', $response->json());

        // If the response is successful, store the email in the session and redirect
        if ($response->successful()) {
            session(['email' => $validate['email']]);
            return Redirect::route('verify')->with(['email' => $validate['email']]);
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP and email
        $validate = $request->validate([
            'otp' => 'string|required',
            'email' => 'email|required',
        ]);

        // Send the OTP verification request to the API
        $response = Http::post(env('API_BASE_URL') . '/verify-otp', [
            'otp' => $validate['otp'],
            'email' => $validate['email'],
        ]);

        // Debugging: Log the API response to ensure the token is being returned
        Log::info('Verify OTP API Response: ', $response->json());

        // If the OTP is verified successfully
        if ($response->successful()) {
            $token = $response->json('token');
            $user_id = $response->json('user_id');
            $restaurant_id = $response->json('restaurant_id');

            // Debugging: Ensure the token is received
            if (!$token || !$user_id || !$restaurant_id) {
                Log::error('Incomplete data received from API');
                return redirect()->route('login')->withErrors(['message' => 'Incomplete data received from API.']);
            }

            // Store the token and other values in Session
            Session::put('token', $token, now()->addMinutes(600));
            Session::put('user_id', $user_id, now()->addMinutes(600));
            Session::put('restaurant_id', $restaurant_id, now()->addMinutes(600));

            // Debugging: Log the Session values to ensure they are stored
            Log::info('Token Stored in Session: ' . Session::get('token'));
            Log::info('User ID Stored in Session: ' . Session::get('user_id'));
            Log::info('Restaurant ID Stored in Session: ' . Session::get('restaurant_id'));

            // Check if Session has values
            if (!Session::has('token') || !Session::has('user_id') || !Session::has('restaurant_id')) {
                Log::error('Failed to store data in Session');
                return redirect()->route('login')->withErrors(['message' => 'Failed to store data in Session.']);
            }

            // Optionally store email in the session
            session(['email' => $validate['email']]);

            // Redirect to the dashboard after successful OTP verification and data storage
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->withErrors(['message' => 'Authentication failed']);
        }
    }

    public function logout(Request $request)
    {
        // Clear the Sessiond token, user_id, and restaurant_id
        Session::forget('token');
        Session::forget('user_id');
        Session::forget('restaurant_id');

        // Clear the session
        $request->session()->flush();

        // Redirect to the login page
        return redirect()->route('login')->with('message', 'Logged out successfully.');
    }
    public function getAuth(){
        $token = Session::get('token');
        $restaurantId = Session::get('restaurant_id');
        $app_url = env('API_BASE_URL');

        return response()->json([
            'token'=>$token,
            'restaurantId'=>$restaurantId,
            'app_url'=>$app_url,
        ]);
    }
}
