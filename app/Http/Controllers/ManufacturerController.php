<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Http\Requests\StoreManufacturerRequest;
class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Manufacturer/Index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManufacturerRequest $request)
    {
        $validatedData = $request->validated();

        $manufacturer = Manufacturer::create($validatedData);

        return response()->json([
            'message' => 'Manufacturer created successfully!',
            'manufacturer' => $manufacturer // Optionally return the created manufacturer data
        ], 201); // 201 Created status code
    }
    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {
        $manufacturer = Manufacturer::findOrFail($manufacturer->id);

        if (!$manufacturer) {
            return redirect()->back()->with('error', 'Manufacturer not found.');
        }

        return response()->json($manufacturer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        //
    }
}
