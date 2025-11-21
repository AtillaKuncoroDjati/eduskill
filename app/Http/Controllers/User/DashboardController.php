<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kursus;
use App\Models\UserCourse;
use App\Models\UserContentProgress;
use App\Models\UserQuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->permission === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Dashboard untuk Admin
     */
    private function adminDashboard()
    {
        $totalCompleted = UserCourse::where('status', 'completed')->count();
        $totalEnrollment = UserCourse::count();

        $stats = [
            'total_kursus' => Kursus::count(),
            'total_user' => User::where('permission', 'user')->count(),
            'total_enrollment' => $totalEnrollment,
            'kursus_aktif' => Kursus::where('status', 'aktif')->count(),
            'completion_rate' => $totalEnrollment > 0 ? round(($totalCompleted / $totalEnrollment) * 100) : 0,
        ];

        $kursusPopuler = Kursus::withCount('userCourses')
            ->orderBy('user_courses_count', 'desc')
            ->take(5)
            ->get();

        $userAktif = User::where('permission', 'user')
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        $enrollmentData = $this->getEnrollmentChartData();

        $completionData = $this->getCompletionRateData();

        $recentActivities = $this->getRecentActivities();

        return view('user.dashboard.index', compact(
            'stats',
            'kursusPopuler',
            'userAktif',
            'enrollmentData',
            'completionData',
            'recentActivities'
        ));
    }

    /**
     * Dashboard untuk User
     */
    private function userDashboard()
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

    /**
     * Get enrollment data per bulan untuk chart admin
     */
    private function getEnrollmentChartData()
    {
        $data = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = UserCourse::whereYear('enrolled_at', $date->year)
                ->whereMonth('enrolled_at', $date->month)
                ->count();

            $data->push([
                'month' => $date->format('M Y'),
                'count' => $count,
            ]);
        }

        return $data;
    }

    /**
     * Get completion rate per kursus
     */
    private function getCompletionRateData()
    {
        return Kursus::withCount([
            'userCourses',
            'userCourses as completed_count' => function ($query) {
                $query->where('status', 'completed');
            }
        ])
            ->having('user_courses_count', '>', 0)
            ->orderBy('user_courses_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($kursus) {
                return [
                    'title' => $kursus->title,
                    'total' => $kursus->user_courses_count,
                    'completed' => $kursus->completed_count,
                    'rate' => $kursus->user_courses_count > 0
                        ? round(($kursus->completed_count / $kursus->user_courses_count) * 100)
                        : 0
                ];
            });
    }

    /**
     * Get recent activities (enrollment, completion, quiz)
     */
    private function getRecentActivities()
    {
        $activities = collect();

        $enrollments = UserCourse::with(['user', 'kursus'])
            ->orderBy('enrolled_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($uc) {
                return [
                    'type' => 'enrollment',
                    'user' => $uc->user->name,
                    'kursus' => $uc->kursus->title,
                    'date' => $uc->enrolled_at,
                    'icon' => 'ti-user-plus',
                    'color' => 'info'
                ];
            });

        $completions = UserCourse::with(['user', 'kursus'])
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($uc) {
                return [
                    'type' => 'completion',
                    'user' => $uc->user->name,
                    'kursus' => $uc->kursus->title,
                    'date' => $uc->completed_at,
                    'icon' => 'ti-trophy',
                    'color' => 'success'
                ];
            });

        return $activities->merge($enrollments)
            ->merge($completions)
            ->sortByDesc('date')
            ->take(10);
    }

    /**
     * Get progress chart data untuk user (existing)
     */
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
