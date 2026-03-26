<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'lesson_id',
        'type',
        'name',
        'path',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
