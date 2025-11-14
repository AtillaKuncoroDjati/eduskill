<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Kursus;

class MateriController extends Controller
{
    public function materi_index($id)
    {
        $kursus = Kursus::where('id', $id)->first();
        return view('admin.materi.index', compact('kursus'));
    }
}
