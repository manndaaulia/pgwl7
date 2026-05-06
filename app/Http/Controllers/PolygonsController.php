<?php

namespace App\Http\Controllers;

use App\Models\PolygonsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PolygonsController extends Controller
{
    protected $polygons;

    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDASI
        $request->validate(
            [
                'geometry_polygon' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_polygon.required' => 'Geometry wajib diisi.',
                'name.required' => 'Nama wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
            ]
        );

        // HANDLE UPLOAD IMAGE
        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('images', 'public');
        }

        // DATA
        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ];

        // SIMPAN
        try {
            $this->polygons->create($data);

            return redirect()->route('peta')
                ->with('success', 'Data polygon berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function geojson()
    {
        $polygons = $this->polygons->all();

        $features = [];

        foreach ($polygons as $polygon) {
            $geom = DB::select("SELECT ST_AsGeoJSON(geom) as geom FROM polygons WHERE id = ?", [$polygon->id]);

            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($geom[0]->geom),
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'image' => $polygon->image,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $polygon = $this->polygons->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = $polygon->image;

        if ($request->hasFile('image')) {
            if ($polygon->image) {
                Storage::disk('public')->delete($polygon->image);
            }
            $imageName = $request->file('image')->store('images', 'public');
        }

        $polygon->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('peta')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Delete data
     */
    public function destroy($id)
    {
        $polygon = $this->polygons->findOrFail($id);

        if ($polygon->image) {
            Storage::disk('public')->delete($polygon->image);
        }

        $polygon->delete();

        return redirect()->route('peta')->with('success', 'Data berhasil dihapus');
    }
}
