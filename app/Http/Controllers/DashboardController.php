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
    
}
