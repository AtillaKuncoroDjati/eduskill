<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Content;
use App\Models\Module;
use App\Models\QuizOption;
use App\Models\QuizQuestion;

class ContentController extends Controller
{
    public function quiz(Content $content)
    {
        $content->load('questions.options');
        return response()->json($content);
    }

    public function store(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required',
            'type' => 'in:text,quiz',
        ]);

        $order = Content::where('module_id', $module->id)->max('order') + 1;

        $content = Content::create([
            'module_id' => $module->id,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'order' => $order,
        ]);

        if ($request->type === 'quiz') {
            foreach ($request->questions as $qIndex => $question) {

                $q = QuizQuestion::create([
                    'content_id' => $content->id,
                    'question' => $question['text'],
                    'order' => $qIndex,
                ]);

                foreach ($question['options'] as $opt) {
                    QuizOption::create([
                        'question_id' => $q->id,
                        'option_text' => $opt['text'],
                        'is_correct' => isset($opt['is_correct']) ? 1 : 0,
                    ]);
                }
            }
        }

        session()->flash('success_message', 'Materi berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:contents,id',
            'title' => 'nullable|string|max:255',
            'type' => 'in:text,quiz',
            'content' => 'required',
        ]);

        $content = Content::findOrFail($request->id);

        $content->update([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
        ]);

        if ($request->type !== 'quiz') {
            session()->flash('success_message', 'Materi telah diperbarui.');
            return back();
        }

        foreach ($content->questions as $q) {
            $q->options()->delete();
            $q->delete();
        }

        foreach ($request->questions as $qIndex => $question) {

            $newQ = \App\Models\QuizQuestion::create([
                'content_id' => $content->id,
                'question' => $question['text'],
                'order' => $qIndex,
            ]);

            foreach ($question['options'] as $opt) {
                \App\Models\QuizOption::create([
                    'question_id' => $newQ->id,
                    'option_text' => $opt['text'],
                    'is_correct' => isset($opt['is_correct']) ? 1 : 0,
                ]);
            }
        }

        session()->flash('success_message', 'Materi & Soal Kuis telah diperbarui.');
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
