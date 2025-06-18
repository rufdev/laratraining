<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
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
    public function show(Asset $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $location)
    {
        //
    }
}
