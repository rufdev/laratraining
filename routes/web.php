<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
});

Route::middleware(['auth', 'verified', 'role:super_admin'])->group(function () {
    Route::post('categories/list', [CategoryController::class, 'list'])->name('categories.list');
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
});

Route::middleware(['auth', 'verified', 'role:super_admin,inventory_manager'])->group(function () {
    Route::post('manufacturers/list', [ManufacturerController::class, 'list'])->name('manufacturers.list');
    Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);

    Route::post('locations/list', [LocationController::class, 'list'])->name('locations.list');
    Route::resource('locations', LocationController::class)->except(['create', 'edit']);
});

Route::middleware(['auth', 'verified', 'role:super_admin,inventory_user'])->group(function () {
    Route::post('assets/list', [AssetController::class, 'list'])->name('assets.list');
    Route::resource('assets', AssetController::class)->except(['create', 'edit']);
});

// Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
// Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);
// Route::resource('locations', LocationController::class)->except(['create', 'edit']);
// Route::resource('assets', AssetController::class)->except(['create', 'edit']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';



// Route::get('/about', function () {
//     return "This is about us page";
// });

// Route::get("/user/{id}", function ($id) {
//     return "User $id";
// });