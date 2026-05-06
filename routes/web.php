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

// ✅ Tambahan: halaman tentang
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');

// =======================
// STORE DATA
// =======================
Route::post('/store-points', [PointsController::class, 'store'])->name('points.store');
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])->name('points.delete');

Route::post('/store-polylines', [PolylinesController::class, 'store'])->name('polylines.store');
Route::delete('/delete-polylines/{id}', [PolylinesController::class, 'destroy'])->name('polylines.delete');

Route::post('/store-polygons', [PolygonsController::class, 'store'])->name('polygons.store');
Route::delete('/delete-polygons/{id}', [PolygonsController::class, 'destroy'])->name('polygons.delete');

// =======================
// DELETE DATA
// =======================
// ✅ Tambahan: hapus point
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])->name('points.delete');

// =======================
// GEOJSON
// =======================
Route::get('/geojson/points', [PointsController::class, 'geojson'])->name('geojson_points');
Route::get('/geojson/polylines', [PolylinesController::class, 'geojson'])->name('geojson_polylines');
Route::get('/geojson/polygons', [PolygonsController::class, 'geojson'])->name('geojson_polygons');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
