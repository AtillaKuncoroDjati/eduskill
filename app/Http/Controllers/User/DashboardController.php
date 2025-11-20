<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserContentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $stats = [
            'total_kursus' => $user->enrolledCourses()->count(),
            'kursus_berlangsung' => $user->enrolledCourses()->whereIn('status', ['enrolled', 'in_progress'])->count(),
            'kursus_selesai' => $user->enrolledCourses()->where('status', 'completed')->count(),
            'total_materi_selesai' => $user->contentProgress()->where('is_completed', true)->count(),
        ];

        $semuaKursus = $user->enrolledCourses()
            ->with(['kursus'])
            ->orderByRaw("CASE
                WHEN status = 'in_progress' THEN 1
                WHEN status = 'enrolled' THEN 2
                WHEN status = 'completed' THEN 3
                END")
            ->orderBy('updated_at', 'desc')
            ->get();

        $progressData = $this->getProgressChartData($user->id);

        return view('user.dashboard.index', compact('stats', 'semuaKursus', 'progressData'));
    }

    private function getProgressChartData($userId)
    {
        $last7Days = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = UserContentProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->whereDate('completed_at', $date->format('Y-m-d'))
                ->count();

            $last7Days->push([
                'date' => $date->format('d M'),
                'count' => $count,
            ]);
        }

        return $last7Days;
    }
}
