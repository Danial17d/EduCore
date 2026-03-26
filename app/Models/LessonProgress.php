<?php

namespace App\Models;

use App\Observers\LessonProgressObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([LessonProgressObserver::class])]
class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'lesson_id',
        'user_id',
        'completed_at',
    ];

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
