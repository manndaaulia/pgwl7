<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    protected $points; // deklarasi property

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->points = new pointsModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'geometry_point' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
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

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get the uploaded image
        if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
        $image->move('storage/images', $name_image);
        } else {
        $name_image = null;
        }
        $data = [
            'geom' => $request->geometry_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // simpan data ke database
        if (!$this->points->create($data)) {
        // kembali ke halaman peta
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data point.');
        }

        // kembali ke halaman peta dengan pesan sukses
        return redirect()->route('peta')->with('success', 'Data point berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
