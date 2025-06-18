<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;
class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Location/Index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $validatedData = $request->validated();

        $location = Location::create($validatedData);

        return response()->json([
            'message' => 'Location created successfully!',
            'location' => $location // Optionally return the created location data
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $location = Location::findOrFail($location->id);

        if (!$location) {
            return redirect()->back()->with('error', 'Location not found.');
        }

        return response()->json($location);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        $category->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => $category->fresh() // Return the fresh, updated category data
        ], 200); // 200 OK status code for successful updates
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
