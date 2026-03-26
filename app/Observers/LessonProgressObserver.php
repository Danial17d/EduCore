<?php

namespace App\Observers;


use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;

class LessonProgressObserver
{
    public function created(LessonProgress $lessonProgress) : void
    {
        $courseId = Lesson::whereKey($lessonProgress->lesson_id)->value('course_id');

        if (!$courseId) {
            return;
        }

        $completedLessons = LessonProgress::query()
            ->join('lessons', 'lessons.id', '=', 'lesson_progress.lesson_id')
            ->where('lessons.course_id', $courseId)
            ->where('lesson_progress.user_id', $lessonProgress->user_id)
            ->whereNotNull('lesson_progress.completed_at')
            ->count();

        $totalLessons = Lesson::where('course_id', $courseId)->count();

        if ($totalLessons === 0) {
            return;
        }

        $percent = (int) round(($completedLessons / $totalLessons) * 100);

        $enrollment = Enrollment::query()
            ->where('course_id', $courseId)
            ->where('user_id', $lessonProgress->user_id)
            ->first();

        if (!$enrollment) {
            return;
        }

        if ($percent === 100) {
            if (!$enrollment->completed_at) {
                $enrollment->completed_at = now();
                $enrollment->save();
            }
            return;
        }

        if ($enrollment->completed_at) {
            $enrollment->completed_at = null;
            $enrollment->save();
        }

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(LessonProgress $lessonProgress): void
    {
        $courseId = Lesson::whereKey($lessonProgress->lesson_id)->value('course_id');

        if (!$courseId) {
            return;
        }

        $completedLessons = LessonProgress::query()
            ->join('lessons', 'lessons.id', '=', 'lesson_progress.lesson_id')
            ->where('lessons.course_id', $courseId)
            ->where('lesson_progress.user_id', $lessonProgress->user_id)
            ->whereNotNull('lesson_progress.completed_at')
            ->count();

        $totalLessons = Lesson::where('course_id', $courseId)->count();

        if ($totalLessons === 0) {
            return;
        }

        $percent = (int) round(($completedLessons / $totalLessons) * 100);

        Enrollment::query()
            ->where('course_id', $courseId)
            ->where('user_id', $lessonProgress->user_id)
            ->update([
                'completed_at' => $percent === 100 ? now() : null,
            ]);
    }


    /**
     * Handle the User "deleted" event.
     */
    public function deleted(): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(): void
    {
        //
    }

}
