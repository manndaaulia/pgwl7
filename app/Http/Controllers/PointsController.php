<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel();
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'geometry_point' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_point.required' => 'Geometry point is required.',
                'name.required' => 'Name is required.',
                'name.string' => 'Name must be a string.',
                'name.max' => 'Name must be at most 255 characters.',
                'description.required' => 'Description is required.',
                'description.string' => 'Description must be a string.',
            ]
        );

        // Handle upload image
        $name_image = null;

        if ($request->hasFile('image')) {
            $name_image = $request->file('image')->store('images', 'public');
        }

        $data = [
            'geom' => $request->geometry_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        try {
            $this->points->create($data);

            return redirect()->route('peta')->with('success', 'Data point berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function geojson()
    {
        $points = $this->points->all();

        $features = [];

        foreach ($points as $point) {
            $geom = DB::select("SELECT ST_AsGeoJSON(geom) as geom FROM points WHERE id = ?", [$point->id]);

            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($geom[0]->geom),
                'properties' => [
                    'id' => $point->id,
                    'name' => $point->name,
                    'description' => $point->description,
                    'image' => $point->image,
                    'created_at' => $point->created_at,
                    'updated_at' => $point->updated_at,
                ],
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $point = $this->points->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = $point->image;

        if ($request->hasFile('image')) {
            if ($point->image) {
                Storage::disk('public')->delete($point->image);
            }
            $imageName = $request->file('image')->store('images', 'public');
        }

        $point->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('peta')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //mencari nama file gambar
        $image = $this->points->find($id)->image;

        //menghapus file gambar jika ada
        if ($image != null) {
            if (file_exists('./storage/images/' . $image)) {
                unlink('./storage/images/' . $image);
            }
        }

        //menghapus data dari database
        if (!$this->points->destroy($id)) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data point.');
        }

        //kembali ke halaman peta
        return redirect()->route('peta')
            ->with('success', 'Data point berhasil dihapus.');
    }
}
