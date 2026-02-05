<?php

namespace App\Observers;

use App\Models\Enrollment;
use Carbon\Carbon;

class EnrollmentObserver
{
    /**
     * Handle the Enrollment "created" event.
     */
    public function created(Enrollment $enrollment): void
    {
        //
    }

    /**
     * Handle the Enrollment "updated" event.
     */
    public function updated(Enrollment $enrollment): void
    {

        if (!$enrollment->wasChanged('completed_at') || !$enrollment->completed_at) {
            return;
        }

        $user = $enrollment->user;
        $course = $enrollment->course;

        if (!$user || !$course) {
            return;
        }
        $expiryDate = Carbon::parse($enrollment->completed_at)->addYears(3);

        $user->certificates()->firstOrCreate(
            [
                'course_id' => $course->id,
            ],
            [
                'name' => $course->name ?? 'Course Completion',
                'issuer' => config('app.name', 'EduCore'),
                'issue_date' => $enrollment->completed_at->toDateString(),
                'expiry_date' => $expiryDate->toDateString(),
                'description' => $course->description ?? null,
            ]
        );

    }

    /**
     * Handle the Enrollment "deleted" event.
     */
    public function deleted(Enrollment $enrollment): void
    {
        //
    }

    /**
     * Handle the Enrollment "restored" event.
     */
    public function restored(Enrollment $enrollment): void
    {
        //
    }

    /**
     * Handle the Enrollment "force deleted" event.
     */
    public function forceDeleted(Enrollment $enrollment): void
    {
        //
    }
}
