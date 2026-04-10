<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnrollmentController extends Controller
{
    public function index()
    {
        Gate::authorize(PermissionType::EnrollmentList);

        $query = Enrollment::query()
            ->with(['course' => function ($builder) {
                $builder->withCount('lessons')->with('category');
            }])
            ->orderByDesc('created_at');

        if (! auth()->user()?->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        $completedLessonsSub = LessonProgress::query()
            ->selectRaw('count(*)')
            ->join('lessons', 'lessons.id', '=', 'lesson_progress.lesson_id')
            ->whereColumn('lessons.course_id', 'enrollments.course_id')
            ->whereColumn('lesson_progress.user_id', 'enrollments.user_id')
            ->whereNotNull('lesson_progress.completed_at');

        $enrollments = $query
            ->select('enrollments.*')
            ->selectSub($completedLessonsSub, 'completed_lessons_count')
            ->paginate(10);

        return view('enrollments.index', [
            'enrollments' => $enrollments,

        ]);

    }
    public function store(Request $request)
    {
        Gate::authorize(PermissionType::EnrollmentCreate);

        $validated = $request->validate([
            'course_slug' => ['required','string', 'exists:courses,slug'],
        ]);

        $course = Course::where('slug', $request->course_slug)->firstOrFail();

        $alreadyEnrolled = Enrollment::query()
            ->where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('enrollments.index')->with('status', 'course-already-enrolled');
        }

        // Block enrollment if active credits would exceed 18
        if ($course->credit) {
            $activeCredits = Enrollment::query()
                ->where('user_id', auth()->id())
                ->whereNull('completed_at')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->sum('courses.credit');

            if (($activeCredits + $course->credit) > 18) {
                return redirect()->back()->with('status', 'credit-limit-exceeded');
            }
        }

        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('enrollments.index')->with('status', 'course-enrolled-successfully');



    }
}
