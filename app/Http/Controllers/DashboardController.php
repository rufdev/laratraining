<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Models\Asset;
use App\Models\Manufacturer;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = CategoryResource::collection(Category::orderBy('name', 'asc')->paginate($request->query('per_page', 5)));


        return inertia('Dashboard', [
            'items' => $categories,
        ]);
    }

    public function stats(){
        // Fetch total counts for various entities
        $totalAssets = Asset::count(); // Count total assets in the database
        $totalCategories = Category::count(); // Count total categories in the database
        $totalManufacturers = Manufacturer::count(); // Count total manufacturers in the database
        $totalLocations = Location::count(); // Count total locations in the database
        $totalUsers = User::count(); // Count total users in the database

        // Fetch asset counts grouped by status
        $assetsByStatus = Asset::select('status', DB::raw('count(*) as total')) // Select status and count of assets
            ->groupBy('status') // Group assets by their status
            ->get(); // Retrieve the grouped data

        // Fetch asset counts grouped by category
        $assetsByCategory = Asset::select('category_id', DB::raw('count(*) as total')) // Select category ID and count of assets
            ->groupBy('category_id') // Group assets by their category ID
            ->with('category:id,name') // Include the category name in the result
            ->get(); // Retrieve the grouped data

        // Fetch asset counts grouped by location
        $assetsByLocation = Asset::select('location_id', DB::raw('count(*) as total')) // Select location ID and count of assets
            ->groupBy('location_id') // Group assets by their location ID
            ->with('location:id,name') // Include the location name in the result
            ->get(); // Retrieve the grouped data

        // Fetch asset counts grouped by assigned user
        $assetsByAssignedUser = Asset::select('assigned_to_user_id', DB::raw('count(*) as total')) // Select assigned user ID and count of assets
            ->groupBy('assigned_to_user_id') // Group assets by their assigned user ID
            ->with('assignedTo:id,name') // Include the user name in the result
            ->get(); // Retrieve the grouped data

        // Return the data as a JSON response
        return response()->json([
            'totals' => [
                'total_assets' => $totalAssets, // Total number of assets
                'total_categories' => $totalCategories, // Total number of categories
                'total_manufacturers' => $totalManufacturers, // Total number of manufacturers
                'total_locations' => $totalLocations, // Total number of locations
                'total_users' => $totalUsers, // Total number of users
            ],
            'charts' => [
                'assets_by_status' => $assetsByStatus, // Chart data for assets grouped by status
                'assets_by_category' => $assetsByCategory, // Chart data for assets grouped by category
                'assets_by_location' => $assetsByLocation, // Chart data for assets grouped by location
                'assets_by_assigned_user' => $assetsByAssignedUser, // Chart data for assets grouped by assigned user
            ],
        ]);
    }
    
}
