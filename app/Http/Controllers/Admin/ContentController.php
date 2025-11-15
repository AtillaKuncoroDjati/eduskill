<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Content;
use App\Models\Module;


class ContentController extends Controller
{
    public function store(Request $request, Module $module)
    {
        $request->validate([
            'type' => 'required|in:text,image,video,quiz',
            'content' => 'required',
        ]);

        $order = Content::where('module_id', $module->id)->max('order') + 1;

        // File upload (image only)
        if ($request->type === 'image' && $request->hasFile('content')) {
            $filename = uniqid() . '.' . $request->file('content')->getClientOriginalExtension();
            $request->file('content')->move(public_path('uploads/materi/'), $filename);
            $content = $filename;
        } else {
            $content = $request->content;
        }

        Content::create([
            'module_id' => $module->id,
            'type' => $request->type,
            'content' => $content,
            'order' => $order,
        ]);

        session()->flash('success_message', 'Materi berhasil ditambahkan.');
        return back();
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->orders as $order => $id) {
            Content::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function delete(Request $request)
    {
        Content::find($request->id)->delete();

        session()->flash('success_message', 'Materi telah dihapus.');
        return back();
    }
}
