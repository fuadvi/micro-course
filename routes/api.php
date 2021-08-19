<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MentorControler;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// mentor
Route::get('mentors', [MentorControler::class, 'index']);
Route::get('mentors/{id}', [MentorControler::class, 'show']);
Route::post('mentors', [MentorControler::class, 'create']);
Route::put('mentors/{id}', [MentorControler::class, 'update']);
Route::delete('mentors/{id}', [MentorControler::class, 'destroy']);

// course
Route::get('course', [CourseController::class, 'index']);
Route::get('course/{id}', [CourseController::class, 'show']);
Route::post('course', [CourseController::class, 'create']);
Route::put('course/{id}', [CourseController::class, 'update']);
Route::delete('course/{id}', [CourseController::class, 'destroy']);

// chapter
Route::get('chapter', [ChapterController::class, 'index']);
Route::get('chapter/{id}', [ChapterController::class, 'show']);
Route::post('chapter', [ChapterController::class, 'create']);
Route::put('chapter/{id}', [ChapterController::class, 'update']);
Route::delete('chapter/{id}', [ChapterController::class, 'destroy']);

// lesson
Route::get('lesson', [LessonController::class, 'index']);
Route::get('lesson/{id}', [LessonController::class, 'show']);
Route::post('lesson', [LessonController::class, 'create']);
Route::put('lesson/{id}', [LessonController::class, 'update']);
Route::delete('lesson/{id}', [LessonController::class, 'destroy']);

// image
Route::post('image-course', [ImageCourseController::class, 'create']);
Route::delete('image-course/{id}', [ImageCourseController::class, 'destroy']);

// my course
Route::get('my-course', [MyCourseController::class, 'index']);
Route::post('my-course', [MyCourseController::class, 'create']);
Route::post('my-course/premium', [MyCourseController::class, 'createPremiumAccess']);

// reviews
Route::post('reviews', [ReviewController::class, 'create']);
Route::put('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
