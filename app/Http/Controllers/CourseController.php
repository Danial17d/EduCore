<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PHPUnit\Logging\OpenTestReporting\Status;

class CourseController extends Controller
{
    public function index()
    {
        $query = \App\Models\Course::query()
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->select('courses.*')
            ->selectRaw("COALESCE(categories.name, 'General') as category_name")
            ->orderBy('category_name');

        if(! auth()->user()?->hasRole('admin')){
            $query->where('status', 1);
        }
        $courses = $query->get();


        $coursesByCategory = $courses->groupBy('category_name');



        return view('courses.index', [
            'coursesByCategory' =>$coursesByCategory ,
            'courses' => $courses,
        ]);
    }
    public function show(String $slug){

        Gate::authorize(PermissionType::CourseView);

        $course = Course::where('slug',$slug)->firstOrFail();

        $course->load('category');

        return view('courses.show', [
            'course' => $course,
        ]);
    }
    public function create()
    {
        Gate::authorize(PermissionType::CourseCreate);

       $categories = Category::query()
           ->orderByDesc('name')
           ->pluck('name');


        return view('courses.create', [
            'categories' => $categories,
        ]);
    }
    public function store(Request $request){
        Gate::authorize(PermissionType::CourseCreate);
        $validated = $request->validate([
            'category_name' => ['required', 'string','max:255'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string','unique:courses'],
            'credit' => ['required', 'integer' ,'min:1'],
            'status' => ['required', 'string','max:255'],
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['required', 'string','max:255'],
            'slug' => ['nullable', 'string','unique:courses'],
        ]);
        $slug = $validated['slug'] ? $validated['slug'] : Str::slug($validated['name']);

        $path = $request->file('image')->store('courses', 'public');

        $categoryId = Category::where('name', $validated['category_name'])->value('id');

        Course::create([
            'category_id' => $categoryId,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'credit' => $validated['credit'],
            'description' => $validated['description'],
            'slug' => $slug,
            'status' => $validated['status'],
            'image' => $path,
        ]);
        return Redirect::route('courses.index')->with('status', 'course-created');
    }
    public function edit(String $slug){

        Gate::authorize(PermissionType::CourseUpdate);


        $course = Course::with('category')->where('slug',$slug)->firstOrFail();

        return view('courses.edit', [
            'course' => $course,
            'categories' => Category::select('name')->get()
        ]);

    }
    public function update(Request $request, String $slug)
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string','max:255'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'credit' => ['required', 'integer' ,'min:1'],
            'status' => ['required','boolean'],
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['required', 'string','max:255'],
            'slug' => ['nullable', 'string'],
        ]);
        $categoryId = Category::where('name', $validated['category_name'])->first()->id;

        $course = Course::where('slug',$slug)->firstOrFail();

        $slug = $validated['slug'] ?? $slug;

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        $path = $request->file('image')->store('courses', 'public');

        $course->update([
            'category_id' =>$categoryId,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'credit' => $validated['credit'],
            'description' => $validated['description'],
            'slug' => $slug,
            'status' => $validated['status'],
            'image' => $path,
        ]);
        return Redirect::route('courses.index')->with('status', 'course-updated');
    }

    public function destroy(string $slug)
    {
        Gate::authorize(PermissionType::CourseDelete);

        $course = Course::where('slug', $slug)->firstOrFail();

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return Redirect::route('courses.index')->with('status', 'course-deleted');
    }


}
