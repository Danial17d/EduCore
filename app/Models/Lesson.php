<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'duration',
        'video',
        'objectives',
        'resources',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
