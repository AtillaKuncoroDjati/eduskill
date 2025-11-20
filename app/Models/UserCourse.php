<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'kursus_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function contentProgress()
    {
        return $this->hasMany(UserContentProgress::class, 'user_course_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(UserQuizAttempt::class, 'user_course_id');
    }
}
