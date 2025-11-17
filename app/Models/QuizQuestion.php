<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'content_id',
        'question',
        'order'
    ];

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id');
    }
}
