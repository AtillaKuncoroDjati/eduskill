<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kursus_id',
        'title',
        'order',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class)->orderBy('order');
    }
}
