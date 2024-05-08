<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Show the barcode scanning form.
     *
     * @return \Illuminate\View\View
     */
    public function showScanForm()
    {
        return view('inventory.scan');
    }

    /**
     * Process the barcode scanning form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processScanForm(Request $request)
    {
        // Validate the form input (barcode)
        $request->validate([
            'barcode' => 'required|exists:inventories,barcode',
        ]);

        // Retrieve the collection of inventory items from the database
        $inventoryItems = Inventory::all();

        // Extract the scanned barcode from the request
        $scannedBarcode = $request->barcode;

        // Compare the scanned barcode with the collection to identify missing items
        $missingItems = $inventoryItems->reject(function ($item) use ($scannedBarcode) {
            return $item->barcode == $scannedBarcode;
        });

        // If missing items are found, return the list of missing items
        if ($missingItems->isNotEmpty()) {
            return response()->json(['message' => 'Missing items found.', 'missing_items' => $missingItems]);
        } else {
            // Return a response indicating no missing items found
            return response()->json(['message' => 'No missing items found.']);
        }
    }

    /**
     * Clear the inventory table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear(Request $request)
    {
        // Check if the user has the necessary role or permission
        if (!$request->user()->hasRole('admin')) {
            // If the user doesn't have the required role, return an unauthorized response
            return response()->json(['error' => 'Unauthorized. Only admin can clear inventory.'], 403);
        }

        // Ensure that the request is authorized before proceeding
        $this->authorize('clearInventory', Inventory::class);

        // Clear the inventory table
        Inventory::truncate();

        // Return a success response
        return response()->json(['message' => 'Inventory table cleared successfully.']);
    }
}
