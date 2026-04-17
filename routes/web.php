<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolylinesController;
use App\Http\Controllers\PolygonsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/peta', [PageController::class, 'peta'])->name('peta');

Route::get('/table', [PageController::class, 'table'])->name('table');

Route::post('/store-points', [PointsController::class, 'store'])->name('points.store');

Route::post('/store-polylines', [PolylinesController::class, 'store'])->name('polylines.store');

Route::post('/store-polygons', [PolygonsController::class, 'store'])->name('polygons.store');

// ✅ Tambahkan 3 route ini
Route::get('/geojson/points', [PointsController::class, 'geojson'])->name('geojson_points');
Route::get('/geojson/polylines', [PolylinesController::class, 'geojson'])->name('geojson_polylines');
Route::get('/geojson/polygons', [PolygonsController::class, 'geojson'])->name('geojson_polygons');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
