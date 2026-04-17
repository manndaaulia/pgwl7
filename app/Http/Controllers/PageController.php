<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function peta()
    {
        $data = [
            'title' => 'Peta Interaktif',
        ];

        return view('map', $data);
    }

    public function table()
    {
        $data = [
            'title' => 'Tabel Data',
        ];

        return view('table', $data);
    }
}
