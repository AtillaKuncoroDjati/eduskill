<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Kursus;
use App\Models\UserCourse;
use Illuminate\Http\Request;

class ListKursusController extends Controller
{
    public function index()
    {
        return view('user.kursus.index');
    }

    public function show($id)
    {
        $kursus = Kursus::with([
            'modules' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'modules.contents' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'modules.contents.questions' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'modules.contents.questions.options'
        ])
            ->where('id', $id)
            ->where('status', 'aktif')
            ->firstOrFail();

        $totalModules = $kursus->modules->count();
        $totalContents = $kursus->modules->sum(function ($module) {
            return $module->contents->count();
        });
        $estimatedDuration = $totalContents * 15;

        $isEnrolled = false;
        $userCourse = null;

        if (auth()->check()) {
            $isEnrolled = auth()->user()->hasEnrolled($kursus->id);
            $userCourse = auth()->user()->getEnrolledCourse($kursus->id);
        }

        return view('user.kursus.show', compact(
            'kursus',
            'totalModules',
            'totalContents',
            'estimatedDuration',
            'isEnrolled',
            'userCourse'
        ));
    }

    public function enroll($id)
    {
        if (!auth()->check()) {
            session()->flash('failed_message', 'Silakan login terlebih dahulu untuk mendaftar kursus.');
            return to_route('auth.view');
        }

        $kursus = Kursus::where('id', $id)->where('status', 'aktif')->firstOrFail();

        if (auth()->user()->hasEnrolled($kursus->id)) {
            return redirect()->route('user.kursus.learn', $kursus->id);
        }

        UserCourse::create([
            'user_id' => auth()->id(),
            'kursus_id' => $kursus->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        session()->flash('success_message', 'Berhasil mendaftar kursus! Selamat belajar dan semoga sukses.');
        return redirect()->route('user.kursus.learn', $kursus->id);
    }

    public function learn($id)
    {
        if (!auth()->check()) {
            session()->flash('failed_message', 'Silakan login terlebih dahulu untuk mengakses kursus.');
            return to_route('auth.view');
        }

        $kursus = Kursus::with([
            'modules.contents.questions.options'
        ])->findOrFail($id);

        $userCourse = auth()->user()->getEnrolledCourse($kursus->id);

        if (!$userCourse) {
            session()->flash('failed_message', 'Anda belum mendaftar kursus ini.');

            return to_route('user.kursus.show', $kursus->id);
        }

        if ($userCourse->status === 'enrolled') {
            $userCourse->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        $contentProgress = $userCourse->contentProgress()->pluck('is_completed', 'content_id');

        return view('user.kursus.learn', compact('kursus', 'userCourse', 'contentProgress'));
    }

    public function request(Request $request)
    {
        $query = Kursus::where('status', 'aktif');

        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $total = $query->count();

        if ($request->has('start') && $request->has('length')) {
            $query->skip($request->input('start'))->take($request->input('length'));
        }

        $data = $query->get();

        return response()->json([
            'draw' => $request->input('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }
}
