<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'thumbnail',
        'title',
        'short_description',
        'description',
        'category',
        'difficulty',
        'certificate',
        'status',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order', 'asc');
    }
}
