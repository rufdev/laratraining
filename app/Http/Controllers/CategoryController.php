<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $message = "Hello from Server";
        return inertia('Category/Index',[
            'message' => $message
        ]);
    }
}
