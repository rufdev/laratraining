<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Category/Index');
    }

    public function list(Request $request)
    {
        $query = Category::query();

        if ($request->has('searchtext') && !empty($request->input('searchtext'))) {
            $search = $request->input('searchtext');
            $query
                ->whereLike('name', '%'.$search.'%')
                ->orWhereLike('description', '%'.$search.'%');
        }

        if ($request->has('sort_field') && $request->has('sort_direction')) {
            $query->orderBy($request->input('sort_field'), $request->input('sort_direction'));
        } else {
            $query->orderBy('name', 'asc'); // Default sorting
        }

        $categories = CategoryResource::collection(
            $query->orderBy('name', 'asc')->paginate($request->input('per_page', 5))
        );
        
        return $categories;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category // Optionally return the created category data
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = Category::findOrFail($category->id);

        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        return response()->json($category);
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
    public function destroy(Category $category)
    {
        //
    }
}
