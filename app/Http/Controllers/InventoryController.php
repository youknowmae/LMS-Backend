<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = Inventory::all();
        return response()->json($inventoryItems);
//        return Inventory::all();
//        $inventories = Inventory::all();
//        return response()->json($inventories);
    }
    public function enterBarcode(Request $request): JsonResponse
    {




        // Create new inventory item
        $inventoryItem = new Inventory();
        $inventoryItem->barcode = $request->barcode;
        $inventoryItem->accession_number = $request->accession_number;
        $inventoryItem->title = $request->title;
        $inventoryItem->author = $request->author;
        $inventoryItem->location = $request->location;
        $inventoryItem->status = $request->status;
        $inventoryItem->save();

        return response()->json(['message' => 'Barcode entered successfully', 'inventoryItem' => $inventoryItem], 201);
    }

    /**
     * Scan barcode.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function scanBarcode(Request $request): JsonResponse
    {
        // Validate request data
        $request->validate([
            'barcode' => 'required|exists:inventories,barcode',
        ]);

        // Retrieve the inventory item by barcode
        $inventoryItem = Inventory::where('barcode', $request->barcode)->first();

        return response()->json(['message' => 'Barcode scanned successfully', 'inventoryItem' => $inventoryItem]);
    }

    /**
     * Clear history.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clearHistory(Request $request): JsonResponse
    {
        try {
            // Truncate the inventory table
            Inventory::truncate();

            return response()->json(['message' => 'Barcode scan history cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to clear barcode scan history'], 500);
        }
    }

    /**
     * Filter inventory items based on status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function filterByStatus(Request $request): JsonResponse
    {
        // Get status from request
        $status = $request->input('status');

        // Query inventory items based on status
        $inventoryItems = Inventory::where('status', $status)->get();

        return response()->json(['inventoryItems' => $inventoryItems]);
    }

    /**
     * Show the barcode scanning form.
     *
     * @return View
     */
    public function showScanForm(): View
    {
        return view('inventory.scan');
    }

    /**
     * Process the barcode scanning form.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processScanForm(Request $request): JsonResponse
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
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
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
