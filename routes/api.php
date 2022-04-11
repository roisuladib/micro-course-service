<?php

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

Route::get('mentors', 'MentorController@index');
Route::get('mentors/detail/{id}', 'MentorController@show');
Route::post('mentors/create', 'MentorController@create');
Route::put('mentors/update/{id}', 'MentorController@update');
Route::delete('mentors/delete/{id}', 'MentorController@destroy');

Route::get('courses', 'CourseController@index');
Route::post('courses/create', 'CourseController@create');
Route::get('courses/detail/{id}', 'CourseController@show');
Route::put('courses/update/{id}', 'CourseController@update');
Route::delete('courses/delete/{id}', 'CourseController@destroy');

Route::get('chapters', 'ChapterController@index');
Route::get('chapters/detail/{id}', 'ChapterController@show');
Route::post('chapters/create', 'ChapterController@create');
Route::put('chapters/update/{id}', 'ChapterController@update');
Route::delete('chapters/delete/{id}', 'ChapterController@destroy');

Route::get('lessons', 'LessonController@index');
Route::get('lessons/detail/{id}', 'LessonController@show');
Route::post('lessons/create', 'LessonController@create');
Route::put('lessons/update/{id}', 'LessonController@update');
Route::delete('lessons/delete/{id}', 'LessonController@destroy');

Route::delete('image-courses/delete/{id}', 'ImageCourseController@destroy');
Route::post('image-courses/create', 'ImageCourseController@create');

Route::get('my-courses', 'MyCourseController@index');
Route::post('my-courses/create', 'MyCourseController@create');
Route::post('my-courses/premium', 'MyCourseController@createPremiumAccess');

Route::post('reviews/create', 'ReviewController@create');
Route::put('reviews/update/{id}', 'ReviewController@update');
Route::delete('reviews/delete/{id}', 'ReviewController@destroy');