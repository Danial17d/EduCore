<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use Carbon\Carbon;

class LessonProgressController extends Controller
{
    public function store()
    {
        $validated = request()->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
            'completed' => ['required', 'boolean'],
        ]);

        $completedAt = $validated['completed'] ? Carbon::now() : null;

        $lessonProgress = LessonProgress::where('lesson_id', $validated['lesson_id'])
            ->where('user_id', request()->user()->id)
            ->first();

        if ($lessonProgress && $lessonProgress->completed_at) {

            if (request()->expectsJson()) {
                return response()->json(['status' => 'Lesson has already been completed.']);
            }

            return redirect()->back()->with('status', 'Lesson has already been completed.');
        }

        $progress = LessonProgress::updateOrCreate(
            [
                'lesson_id' => $validated['lesson_id'],
                'user_id' => request()->user()->id,
            ],
            [
                'completed_at' => $completedAt,
            ]
        );


        if (request()->expectsJson()) {
            return response()->json(['status' => 'Lesson has successfully been completed.']);
        }

        return redirect()->back()->with('status', 'Lesson has successfully been completed.');
    }

}
