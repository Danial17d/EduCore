<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke()
    {
        Gate::authorize(PermissionType::DashboardView);

        $user = auth()->user();
        $roles = $user->roles->pluck('name')->all();
        $isAdmin = $user->hasRole('admin');
        $isInstructor = $user->hasRole('instructor');
        $isStudent = $user->hasRole('student');

        $assignedCourseIds = $isInstructor
            ? CourseInstructor::query()
                ->where('user_id', $user->id)
                ->pluck('course_id')
            : collect();

        $enrolledCourseIds = $isStudent
            ? Enrollment::query()
                ->where('user_id', $user->id)
                ->pluck('course_id')
            : collect();

        $cards = [];
        $quickActions = [];
        $recentEnrollments = collect();
        $recentCourses = collect();
        $adminCourses = collect();

        if ($isAdmin) {
            $cards = [
                ['label' => 'Total Users', 'value' => User::query()->count()],
                ['label' => 'Total Courses', 'value' => Course::query()->count()],
                ['label' => 'Active Courses', 'value' => Course::query()->where('status', 'active')->count()],
                ['label' => 'Total Enrollments', 'value' => Enrollment::query()->count()],
            ];

            $quickActions = [
                ['label' => 'Create User', 'route' => 'users.create', 'permission' => PermissionType::UserCreate->value],
                ['label' => 'Create Course', 'route' => 'courses.create', 'permission' => PermissionType::CourseCreate->value],
                ['label' => 'View Users', 'route' => 'users.index', 'permission' => PermissionType::UserList->value],
                ['label' => 'View Courses', 'route' => 'courses.index', 'permission' => PermissionType::CourseList->value],

            ];
            $coursesTeachOverview = Course::query()
                ->withCount(['courseInstructors','lessons'])
                ->orderByDesc('course_instructors_count')
                ->take(4)
                ->get();


        } elseif ($isInstructor) {
            $totalAssignedEnrollments = Enrollment::query()
                ->whereIn('course_id', $assignedCourseIds)
                ->count();

            $totalUniqueStudents = Enrollment::query()
                ->whereIn('course_id', $assignedCourseIds)
                ->distinct('user_id')
                ->count('user_id');

            $cards = [
                ['label' => 'Assigned Courses', 'value' => $assignedCourseIds->count()],
                ['label' => 'Course Lessons', 'value' => Lesson::query()->whereIn('course_id', $assignedCourseIds)->count()],
                ['label' => 'Course Enrollments', 'value' => $totalAssignedEnrollments],
                ['label' => 'Unique Students', 'value' => $totalUniqueStudents],
            ];

            $quickActions = [
                ['label' => 'Create Course', 'route' => 'courses.create', 'permission' => PermissionType::CourseCreate->value],
                ['label' => 'View Courses', 'route' => 'courses.index', 'permission' => PermissionType::CourseList->value],
                ['label' => 'My Profile', 'route' => 'profile.show', 'permission' => PermissionType::ProfileView->value],
            ];

            $recentCourses = CourseInstructor::query()
                ->where('user_id', $user->id)
                ->with([
                    'instructor:id,name',
                    'course' => fn ($query) => $query
                        ->select('id', 'name')
                        ->withCount('enrollments'),
                ])
                ->latest()
                ->take(4)
                ->get();
        } else {
            $completedLessons = LessonProgress::query()
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count();

            $creditsEarned = Enrollment::query()
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->sum('courses.credit');

            $creditsInProgress = Enrollment::query()
                ->where('user_id', $user->id)
                ->whereNull('completed_at')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->sum('courses.credit');

            $cards = [
                ['label' => 'Enrolled Courses', 'value' => $enrolledCourseIds->count()],
                [
                    'label' => 'Completed Courses',
                    'value' => Enrollment::query()
                        ->where('user_id', $user->id)
                        ->whereNotNull('completed_at')
                        ->count(),
                ],
                ['label' => 'Credits Earned', 'value' => (int) $creditsEarned],
                ['label' => 'Credits In Progress', 'value' => (int) $creditsInProgress],
            ];

            $quickActions = [
                ['label' => 'Browse Courses', 'route' => 'courses.index', 'permission' => PermissionType::CourseList->value],
                ['label' => 'My Enrollments', 'route' => 'enrollments.index', 'permission' => PermissionType::EnrollmentList->value],
                ['label' => 'My Profile', 'route' => 'profile.show', 'permission' => PermissionType::ProfileView->value],
            ];

            $recentEnrollments = Enrollment::query()
                ->with('course:id,name,credit')
                ->where('user_id', $user->id)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('dashboard', [
            'cards' => $cards,
            'quickActions' => $quickActions,
            'recentEnrollments' => $recentEnrollments,
            'recentCourses' => $recentCourses,
            'coursesTeachOverview' => $coursesTeachOverview,
            'roles' => $roles,
            'creditsEarned' => $creditsEarned ?? 0,
            'creditsInProgress' => $creditsInProgress ?? 0,
        ]);
    }
}
