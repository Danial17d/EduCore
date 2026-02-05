<?php

use App\Enums\PermissionType;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//public routers
Route::group([],function (){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/courses',[CourseController::class,'index'])->name('courses.index');
    Route::get('/communities',[CommunityController::class,'index'])->name('communities.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/media', [MediaController::class, 'update'])->name('media.update');

    Route::post('/experience', [ExperienceController::class, 'store'])->name('experience.store');
    Route::delete('/experience/{experience}', [ExperienceController::class, 'destroy'])->name('experience.destroy');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{slug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{slug}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{slug}', [CourseController::class, 'destroy'])->name('courses.destroy');


});

require __DIR__.'/auth.php';
