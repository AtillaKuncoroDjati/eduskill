<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Kursus;
use App\Models\UserContentProgress;
use App\Models\UserCourse;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizAttempt;
use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $isEnrolled = $user->hasEnrolled($kursus->id);
            $userCourse = $user->getEnrolledCourse($kursus->id);
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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        /** @var User $user */
        $user = Auth::user();

        $kursus = Kursus::where('id', $id)->where('status', 'aktif')->firstOrFail();

        if ($user->hasEnrolled($kursus->id)) {
            return redirect()->route('user.kursus.learn', $kursus->id);
        }

        UserCourse::create([
            'user_id' => Auth::id(),
            'kursus_id' => $kursus->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('user.kursus.learn', $kursus->id)
            ->with('success', 'Berhasil mendaftar kursus!');
    }

    public function learn($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        /** @var User $user */
        $user = Auth::user();

        $kursus = Kursus::with([
            'modules.contents.questions.options'
        ])->findOrFail($id);

        $userCourse = $user->getEnrolledCourse($kursus->id);

        if (!$userCourse) {
            return redirect()->route('user.kursus.show', $kursus->id)
                ->with('error', 'Anda belum mendaftar kursus ini');
        }

        if ($userCourse->status === 'enrolled') {
            $userCourse->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        $contentProgress = $userCourse->contentProgress()->pluck('is_completed', 'content_id')->toArray();

        return view('user.kursus.learn', compact('kursus', 'userCourse', 'contentProgress'));
    }

    public function getContent($kursusId, $contentId)
    {
        $content = Content::with(['questions.options', 'module'])
            ->findOrFail($contentId);

        /** @var User $user */
        $user = Auth::user();

        $userCourse = $user->getEnrolledCourse($kursusId);
        if (!$userCourse) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        if ($content->type === 'text') {
            return response()->json([
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'content' => nl2br($content->content),
            ]);
        } else if ($content->type === 'quiz') {
            $latestAttempt = UserQuizAttempt::where('user_id', Auth::id())
                ->where('content_id', $contentId)
                ->where('user_course_id', $userCourse->id)
                ->where('is_passed', true)
                ->latest()
                ->first();

            if ($latestAttempt) {
                $quizDetails = [];

                foreach ($content->questions as $question) {
                    $userAnswer = UserQuizAnswer::where('quiz_attempt_id', $latestAttempt->id)
                        ->where('question_id', $question->id)
                        ->with('selectedOption')
                        ->first();

                    $quizDetails[] = [
                        'question' => $question->question,
                        'options' => $question->options->map(function ($option) use ($userAnswer) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'is_correct' => $option->is_correct,
                                'is_selected' => $userAnswer && $userAnswer->selected_option_id == $option->id,
                            ];
                        }),
                        'user_is_correct' => $userAnswer ? $userAnswer->is_correct : false,
                    ];
                }

                return response()->json([
                    'id' => $content->id,
                    'title' => $content->title,
                    'type' => $content->type,
                    'already_passed' => true,
                    'attempt' => [
                        'score' => $latestAttempt->score,
                        'correct_answers' => $latestAttempt->correct_answers,
                        'total_questions' => $latestAttempt->total_questions,
                        'completed_at' => $latestAttempt->completed_at->format('d M Y H:i'),
                    ],
                    'quiz_details' => $quizDetails,
                ]);
            }

            return response()->json([
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'already_passed' => false,
                'questions' => $content->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'options' => $question->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                            ];
                        }),
                    ];
                }),
            ]);
        }
    }

    public function markContentComplete($kursusId, $contentId)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCourse = $user->getEnrolledCourse($kursusId);

        if (!$userCourse) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $progress = UserContentProgress::where('user_id', Auth::id())
            ->where('content_id', $contentId)
            ->where('user_course_id', $userCourse->id)
            ->first();

        if (!$progress) {
            $progress = UserContentProgress::create([
                'user_id' => Auth::id(),
                'content_id' => $contentId,
                'user_course_id' => $userCourse->id,
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        } else if (!$progress->is_completed) {
            $progress->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }

        $this->updateCourseProgress($userCourse);

        return response()->json([
            'success' => true,
            'progress' => $userCourse->fresh()->progress_percentage,
        ]);
    }

    public function submitQuiz($kursusId, $contentId, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userCourse = $user->getEnrolledCourse($kursusId);

        if (!$userCourse) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $content = Content::with('questions.options')->findOrFail($contentId);

        if ($content->type !== 'quiz') {
            return response()->json(['error' => 'Not a quiz'], 400);
        }

        $answers = $request->input('answers', []);

        DB::beginTransaction();
        try {
            $attempt = UserQuizAttempt::create([
                'user_id' => Auth::id(),
                'content_id' => $contentId,
                'user_course_id' => $userCourse->id,
                'total_questions' => $content->questions->count(),
                'started_at' => now(),
            ]);

            $correctAnswers = 0;

            foreach ($content->questions as $question) {
                $answerKey = 'question_' . $question->id;
                $selectedOptionId = $answers[$answerKey] ?? null;

                if ($selectedOptionId) {
                    $selectedOption = $question->options->where('id', $selectedOptionId)->first();
                    $isCorrect = $selectedOption && $selectedOption->is_correct;

                    if ($isCorrect) {
                        $correctAnswers++;
                    }

                    UserQuizAnswer::create([
                        'user_id' => Auth::id(),
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $selectedOptionId,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            $score = ($correctAnswers / $content->questions->count()) * 100;
            $isPassed = $score >= 70;

            $attempt->update([
                'correct_answers' => $correctAnswers,
                'score' => round($score),
                'is_passed' => $isPassed,
                'completed_at' => now(),
            ]);

            if ($isPassed) {
                $progress = UserContentProgress::where('user_id', Auth::id())
                    ->where('content_id', $contentId)
                    ->where('user_course_id', $userCourse->id)
                    ->first();

                if (!$progress) {
                    UserContentProgress::create([
                        'user_id' => Auth::id(),
                        'content_id' => $contentId,
                        'user_course_id' => $userCourse->id,
                        'is_completed' => true,
                        'completed_at' => now(),
                    ]);
                } else if (!$progress->is_completed) {
                    $progress->update([
                        'is_completed' => true,
                        'completed_at' => now(),
                    ]);
                }

                $this->updateCourseProgress($userCourse);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'is_passed' => $isPassed,
                'score' => round($score),
                'correct_answers' => $correctAnswers,
                'total_questions' => $content->questions->count(),
                'progress' => $userCourse->fresh()->progress_percentage,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to submit quiz'], 500);
        }
    }

    private function updateCourseProgress(UserCourse $userCourse)
    {
        $kursus = $userCourse->kursus()->with('modules.contents')->first();

        $totalContents = 0;
        foreach ($kursus->modules as $module) {
            $totalContents += $module->contents->count();
        }

        if ($totalContents === 0) {
            return;
        }

        $completedContents = $userCourse->contentProgress()
            ->where('is_completed', true)
            ->count();

        $percentage = round(($completedContents / $totalContents) * 100);

        $userCourse->update([
            'progress_percentage' => $percentage,
        ]);

        if ($percentage >= 100) {
            $userCourse->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
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
