<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'as'   => 'home.index',
    'uses' => 'HomeController@index',
]);

// Courses routes.
Route::group(['namespace' => 'Courses', 'middleware' => ['auth', 'courses.permissions']], function () {
    // Courses.
    Route::resource('courses', 'CoursesController');

    // Assignments.
    Route::get('/courses/{slug}/assignments', [
        'as'   => 'courses.assignments.index',
        'uses' => 'AssignmentsController@index',
    ]);
    Route::get('/courses/{slug}/assignments/create', [
        'as'   => 'courses.assignments.create',
        'uses' => 'AssignmentsController@create',
    ]);
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
