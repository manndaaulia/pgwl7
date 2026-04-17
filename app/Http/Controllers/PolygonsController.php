<?php

namespace App\Http\Controllers;

use App\Models\polygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'geometry_polygon' => 'required',
        ]);

        polygonsModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polygon,
        ]);

        return redirect()->route('peta')->with('success', 'Data polygon berhasil disimpan!');
    }
}
