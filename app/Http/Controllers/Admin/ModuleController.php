<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Module;
use App\Models\Kursus;


class ModuleController extends Controller
{
    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $order = Module::where('kursus_id', $kursus->id)->max('order') + 1;

        Module::create([
            'kursus_id' => $kursus->id,
            'title' => $request->title,
            'order' => $order,
        ]);

        session()->flash('success_message', 'Modul baru berhasil ditambahkan ke kursus.');

        return back();
    }

    public function detail($id)
    {
        $module = Module::with('contents')->findOrFail($id);
        return view('admin.module.detail', compact('module'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|string|max:255',
        ]);

        Module::where('id', $request->id)->update([
            'title' => $request->title
        ]);

        session()->flash('success_message', 'Modul berhasil diperbarui.');

        return back();
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->orders as $order => $id) {
            Module::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function delete(Request $request)
    {
        Module::find($request->id)->delete();

        session()->flash('success_message', 'Modul telah dihapus.');

        return back();
    }
}
