<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'quiz_attempt_id',
        'question_id',
        'selected_option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quizAttempt()
    {
        return $this->belongsTo(UserQuizAttempt::class, 'quiz_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuizOption::class, 'selected_option_id');
    }
}
