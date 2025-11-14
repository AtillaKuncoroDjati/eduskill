<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kursus_id',
        'title',
        'content',
        'order',
        'status',
        'is_quiz',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }
}
