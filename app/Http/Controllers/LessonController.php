<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Attachment;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index(String $slug)
    {
        Gate::authorize(PermissionType::LessonList);

        $course = Course::query()
            ->with(['lessons', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedCourses = Course::query()
            ->with('category')
            ->withCount('lessons')
            ->where('id', '!=', $course->id)
            ->when($course->category_id, function ($query) use ($course) {
                $query->where('category_id', $course->category_id);
            })
            ->orderByDesc('updated_at');

        if (! auth()->user()?->hasAnyRole(['admin', 'instructor'])) {
            $relatedCourses
                ->where('status', 'active')
                ->whereHas('lessons')
                ->whereHas('courseInstructors');
        }

        return view('lessons.index', [
            'course' => $course,
            'lessons' => $course->lessons,
            'relatedCourses' => $relatedCourses->paginate(12),
        ]);
    }
    public function show(String $slug)
    {
        Gate::authorize(PermissionType::LessonView);

        $lesson = Lesson::where('slug', $slug)->firstOrFail();

        $lesson->load('attachments', 'course','progress');

        $nextLesson = Lesson::query()
            ->where('course_id', $lesson->course_id)
            ->where('id', '>', $lesson->id)
            ->orderBy('id')
            ->first();

        return view('lessons.show', [
            'lesson' => $lesson,
            'nextLesson' => $nextLesson,
        ]);
    }

    public function create(String $slug)
    {
        Gate::authorize(PermissionType::LessonCreate);

        $course = Course::where('slug', $slug)->firstOrFail();

        return view('lessons.create', [
            'course' => $course,
        ]);
    }

    public function store(StoreLessonRequest $request)
    {

        $validated = $request->validated();

        $courseId = Course::where('slug', $validated['course_slug'])->firstOrFail();

        $videoPath = "";

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('lesson', 'public');

        }
        $lesson = Lesson::create([
            'course_id' => $courseId->id,
            'title' => $validated['title'],
            'slug' =>Str::slug($validated['title']),
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'video' => $videoPath,
            'objectives' => $validated['objectives'] ?? null,
            'resources' => $validated['resources'] ?? null,

        ]);


       if ($request->hasFile('attachments')) {

           foreach ($validated['attachments'] as $attachment) {
               $path = $attachment->store('attachments', 'public');
               Attachment::create([
                   'lesson_id' => $lesson->id,
                   'type' => $attachment->getClientOriginalExtension(),
                   'name' => $attachment->getClientOriginalName(),
                   'path' => $path
               ]);
           }


       }

        return Redirect::route('lessons.show', $lesson->slug);
    }
    public function edit(String $slug){
        Gate::authorize(PermissionType::LessonUpdate);

        $lesson = Lesson::where('slug', $slug)->firstOrFail();

        $lesson->load('attachments','course');

        return view('lessons.edit', [
            'lesson' => $lesson,

        ]);


    }
    public function update(UpdateLessonRequest $request, String $slug)
    {
        Gate::authorize(PermissionType::LessonUpdate);

        $lesson = Lesson::where('slug', $slug)->firstOrFail();

        $validated = $request->validated();

        if ($request->hasFile('video')) {
            if ($lesson->video) {
                Storage::disk('public')->delete($lesson->video);
            }
            $lesson->video = $request->file('video')->store('lesson', 'public');
        }

        $lesson->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'objectives' => $validated['objectives'] ?? null,
            'resources' => $validated['resources'] ?? null,
            'video' => $lesson->video,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($validated['attachments'] as $attachment) {
                $path = $attachment->store('attachments', 'public');
                Attachment::create([
                    'lesson_id' => $lesson->id,
                    'type' => $attachment->getClientOriginalExtension(),
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path
                ]);
            }
        }

        return Redirect::route('lessons.show', $lesson->slug)->with('status', 'lesson-updated');
    }

    public function destroy(String $slug)
    {
        Gate::authorize(PermissionType::LessonDelete);

        $lesson = Lesson::with(['attachments', 'course'])->where('slug', $slug)->firstOrFail();

        if ($lesson->video) {
            Storage::disk('public')->delete($lesson->video);
        }

        foreach ($lesson->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

        $courseSlug = $lesson->course?->slug;

        $lesson->delete();

        return $courseSlug
            ? Redirect::route('courses.show', $courseSlug)->with('status', 'lesson-deleted')
            : Redirect::route('courses.index')->with('status', 'lesson-deleted');
    }

}
