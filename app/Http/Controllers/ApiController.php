<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use App\Models\polylinesModel;
use App\Models\PolygonsModel; // ✅ TAMBAH INI
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $points;
    protected $polylines;
    protected $polygons; // ✅ TAMBAH INI

    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polylines = new polylinesModel();
        $this->polygons = new PolygonsModel();
    }

    public function geojson_points()
    {
        $points = $this->points->geojson_points();
        return response()->json($points, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_polylines()
    {
        $polylines = $this->polylines->geojson_polylines();
        return response()->json($polylines, 200, [], JSON_NUMERIC_CHECK);
    }

    // ✅ TAMBAH INI
    public function geojson_polygons()
    {
        $polygons = $this->polygons->geojson_polygons();
        return response()->json($polygons, 200, [], JSON_NUMERIC_CHECK);
    }
}
