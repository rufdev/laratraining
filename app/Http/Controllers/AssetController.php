<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $message = "Hello from AssetController";
        return inertia('Asset/Index',[
            'message' => $message
        ]);
    }
}
