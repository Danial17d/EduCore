<?php

namespace App\Models;

use App\Observers\EnrollmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[ObservedBy([EnrollmentObserver::class])]
class Enrollment extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
    ];
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
