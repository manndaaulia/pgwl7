<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'geometry_point' => 'required',
        ]);

        pointsModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_point,
        ]);

        return redirect()->route('peta')->with('success', 'Data point berhasil disimpan!');
    }
}
