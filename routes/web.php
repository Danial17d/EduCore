<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseInstructorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//public routers
Route::group([],function (){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/courses',[CourseController::class,'index'])->name('courses.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/users',[UserController::class,'index'])->name('users.index');
    Route::get('/users/create',[UserController::class,'create'])->name('users.create');
    Route::post('/users',[UserController::class,'store'])->name('users.store');
    Route::get('/users/{user}',[UserController::class,'show'])->name('users.show');
    Route::get('/users/{user}/edit',[UserController::class,'edit'])->name('users.edit');
    Route::patch('/users/{user}',[UserController::class,'update'])->name('users.update');
    Route::delete('/users/{user}',[UserController::class,'destroy'])->name('users.destroy');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/media', [MediaController::class, 'update'])->name('media.update');

    Route::post('/experience', [ExperienceController::class, 'store'])->name('experience.store');
    Route::patch('/experience/{experience}', [ExperienceController::class, 'update'])->name('experience.update');
    Route::delete('/experience/{experience}', [ExperienceController::class, 'destroy'])->name('experience.destroy');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{slug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{slug}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{slug}', [CourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/lessons/{slug}/related-course', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{slug}', [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/lessons/create/{slug}', [LessonController::class,'create'])->name('lessons.create');
    Route::post('/lessons', [LessonController::class,'store'])->name('lessons.store');
    Route::get('/lessons/{slug}/edit', [LessonController::class,'edit'])->name('lessons.edit');
    Route::patch('/lessons/{slug}', [LessonController::class,'update'])->name('lessons.update');
    Route::delete('/lessons/{slug}', [LessonController::class,'destroy'])->name('lessons.destroy');

    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');

    Route::post('/lesson-progress', [LessonProgressController::class, 'store'])->name('lesson-progress.store');

    Route::get('/instructors-assignment', [CourseInstructorController::class, 'index'])->name('instructors-assignment.index');
    Route::post('/instructors-assignment/{course:slug}', [CourseInstructorController::class, 'store'])->name('instructors-assignment.store');
    Route::delete('/instructors-assignment/{courseInstructor}', [CourseInstructorController::class, 'destroy'])->name('instructors-assignment.destroy');
});

require __DIR__.'/auth.php';
