<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SessionController extends Controller
{
    /**
     * Store or update session data and return all session data as a response.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addData(Request $request)
    {
        // Validate the request based on the type of data provided
        $validator = Validator::make($request->all(), [
            'itemName' => 'required_with:itemId,price|string|max:255',
            'itemId' => 'required_with:itemName,price|integer',
            'price' => 'required_with:itemName,itemId|numeric|min:0',
            'id' => 'nullable|integer',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Add or update customer information in the session
        if ($request->has('id')) {
            session()->put('id', $request->input('id'));
        }
        if ($request->has('name')) {
            session()->put('name', $request->input('name'));
        }

        // Handle items with quantity and price tracking
        $items = session()->get('items', []); // Get the current list of items or initialize as an empty array
        $itemExists = false;

        if ($request->has(['itemId', 'itemName', 'price'])) {
            foreach ($items as &$item) {
                if ((int) $item['itemId'] === (int) $request->input('itemId')) {
                    // If the item exists, increment its quantity
                    $item['quantity'] += 1;
                    $itemExists = true;
                    break;
                }
            }

            // Add a new item if it doesn't exist
            if (!$itemExists) {
                $items[] = [
                    'itemId' => $request->input('itemId'),
                    'itemName' => $request->input('itemName'),
                    'price' => $request->input('price'), // Price of one unit
                    'quantity' => 1, // Initial quantity
                ];
            }

            // Update the session with the modified items array
            session()->put('items', $items);
        }

        // Retrieve all session data
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'items' => session()->get('items'),
        ];

        $message = $itemExists
            ? 'Item quantity updated successfully!'
            : 'New item added to the session.';

        return response()->json([
            'data' => $sessionData,
            'message' => $message,
            'success' => true,
        ]);
    }


    /**
     * Clear all session data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearSession()
    {
        // Remove specific session keys
        session()->forget(['id', 'name', 'items','customer']);

        return redirect()->back()->with('message', 'Selected session data cleared successfully!');
    }

    public function removeItem(Request $request)
    {
        $request->validate([
            'itemId' => 'required|integer',
        ]);

        // Retrieve the current cart items from the session
        $items = session()->get('items', []);

        // Filter out the item to be removed
        $items = array_filter($items, function ($item) use ($request) {
            return $item['itemId'] != $request->input('itemId');
        });

        // Update the session
        session()->put('items', $items);

        return response()->json([
            'message' => 'Item removed successfully!',
            'items' => $items // Optional: return the updated cart items
        ]);
    }
    public function getCart()
    {
        // Retrieve cart items from session
        $items = session()->get('items', []); // Default to empty array if no items exist

        // Construct the response data
        $responseData = [
            'data' => [
                'items' => $items,
            ],
            'message' => 'Cart fetched successfully!',
        ];

        return response()->json($responseData);
    }

    public function getData()
    {
        // Retrieve data from the session
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'items' => session()->get('items', []), // Default to an empty array if no items are found
        ];

        // Check if session data is available
        if (!empty($sessionData['id']) || !empty($sessionData['name']) || !empty($sessionData['items'])) {
            return response()->json([
                'data' => $sessionData,
                'message' => 'Session data retrieved successfully!',
            ]);
        }

        return response()->json([
            'data' => $sessionData,
            'message' => 'No session data found.',
        ]);
    }
}
