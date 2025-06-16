<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('categories', [CategoryController::class, 'index'])->name('categories');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



// Route::get('/about', function () {
//     return "This is about us page";
// });

// Route::get("/user/{id}", function ($id) {
//     return "User $id";
// });