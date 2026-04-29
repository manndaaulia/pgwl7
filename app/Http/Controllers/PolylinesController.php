<?php

namespace App\Http\Controllers;

use App\Models\PolylinesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDASI
        $request->validate(
            [
                'geometry_polylines' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_polylines.required' => 'Geometry wajib diisi.',
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
            'geom' => $request->geometry_polylines,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName, // simpan path image
        ];

        // SIMPAN
        try {
            $this->polylines->create($data);

            return redirect()->route('peta')
                ->with('success', 'Data polyline berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $polyline = $this->polylines->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = $polyline->image;

        // jika upload image baru
        if ($request->hasFile('image')) {

            // hapus image lama
            if ($polyline->image) {
                Storage::disk('public')->delete($polyline->image);
            }

            $imageName = $request->file('image')->store('images', 'public');
        }

        $polyline->update([
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
        $polyline = $this->polylines->findOrFail($id);

        // hapus image
        if ($polyline->image) {
            Storage::disk('public')->delete($polyline->image);
        }

        $polyline->delete();

        return redirect()->route('peta')->with('success', 'Data berhasil dihapus');
    }
}
