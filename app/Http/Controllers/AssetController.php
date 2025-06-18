<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Asset/Index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $validatedData = $request->validated();

        $asset = Asset::create($validatedData);

        return response()->json([
            'message' => 'Asset created successfully!',
            'asset' => $asset // Optionally return the created asset data
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset = Asset::findOrFail($asset->id);

        if (!$asset) {
            return redirect()->back()->with('error', 'Asset not found.');
        }

        return response()->json($asset);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validatedData = $request->validated();

        $asset->update($validatedData);

        return response()->json([
            'message' => 'Asset updated successfully!',
            'asset' => $asset->fresh() // Return the fresh, updated asset data
        ], 200); // 200 OK status code for successful updates
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $asset = Asset::findOrFail($id); // Find the asset or throw a 404 error
            $asset->delete(); // Delete the asset

            return response()->json(['message' => 'Asset deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete asset.'], 500);
        }
    }
}
