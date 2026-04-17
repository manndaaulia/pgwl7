<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'geometry_polyline' => 'required',
        ]);

        polylinesModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polyline,
        ]);

        return redirect()->route('peta')->with('success', 'Data polyline berhasil disimpan!');
    }
}
