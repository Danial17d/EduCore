<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class CourseInstructorController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize(PermissionType::CourseInstructorList);

        $search = trim((string) $request->query('search', ''));

        $query = CourseInstructor::query()
            ->with([
                'course:id,name,slug,code,status,image,category_id',
                'course.category:id,name',
                'instructor:id,name,email',
            ])
            ->whereHas('course')
            ->whereHas('instructor')
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where(function ($inner) use ($search) {
                    $inner->whereHas('course', function ($courseQuery) use ($search) {
                        $courseQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })->orWhereHas('instructor', function ($instructorQuery) use ($search) {
                        $instructorQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            })
            ->latest();

        if (auth()->user()->hasRole('instructor') && ! auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        $courseInstructors = $query->paginate(12)->withQueryString();
        return view('courseInstructors.index', [
            'courseInstructors' => $courseInstructors,
            'search' => $search,
        ]);
    }
    public function store(Request $request, Course $course)
    {
        Gate::authorize(PermissionType::CourseInstructorCreate);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $instructor = User::query()->findOrFail($validated['user_id']);

        if (! $instructor->hasRole('instructor')) {
            return Redirect::back()->withErrors([
                'user_id' => 'Selected user is not an instructor.',
            ]);
        }

        CourseInstructor::query()->firstOrCreate([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
        ]);

        return Redirect::route('courses.show', $course->slug)->with('status', 'instructor-assigned');
    }

    public function destroy(CourseInstructor $courseInstructor)
    {
        Gate::authorize(PermissionType::CourseInstructorDelete);
        $courseInstructor->loadMissing('course:id,slug');
        $courseInstructor->delete();

        return Redirect::route('courses.show', $courseInstructor->course->slug)->with('status', 'instructor-unassigned');
    }
}
