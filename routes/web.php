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

// Edge case to redirect to homepage if someone tries to access /courses.
// Not really needed, but it's a handy addition.
Route::redirect('/courses', '/');

// Courses routes.
Route::group(['namespace' => 'Courses', 'middleware' => ['auth', 'courses.permissions']], function () {
    // Courses.
    Route::resource('courses', 'CoursesController', [
        'only' => [
            'show',
        ],
    ]);

    // Assignments.
    Route::get('/courses/{slug}/assignments', [
        'as'   => 'courses.assignments.index',
        'uses' => 'AssignmentsController@index',
    ]);
    Route::get('/courses/{slug}/assignments/create', [
        'as'   => 'courses.assignments.create',
        'uses' => 'AssignmentsController@create',
    ]);
    Route::post('/courses/{slug}/assignments/create', [
        'as'   => 'courses.assignments.store',
        'uses' => 'AssignmentsController@store',
    ]);
    Route::get('/courses/{slug}/assignments/{assignment}', [
        'as'   => 'courses.assignments.show',
        'uses' => 'AssignmentsController@show',
    ]);
    Route::get('/courses/{slug}/assignments/{assignment}/edit', [
        'as'   => 'courses.assignments.edit',
        'uses' => 'AssignmentsController@edit',
    ]);
    Route::post('/courses/{slug}/assignments/{assignment}', [
        'as'   => 'courses.assignments.update',
        'uses' => 'AssignmentsController@update',
    ]);
    Route::post('/courses/{slug}/assignments/{assignment}/delete', [
        'as'   => 'courses.assignments.destroy',
        'uses' => 'AssignmentsController@destroy',
    ]);
    Route::post('/courses/{slug}/assignments/{assignment}/submit', [
        'as'   => 'courses.assignments.submit',
        'uses' => 'AssignmentsController@submit',
    ]);

    // Attachments.
    Route::get('/courses/{slug}/assignments/{assignment}/attachments/{attachment}', [
        'as'   => 'courses.assignments.attachments.show',
        'uses' => 'FilesController@show',
    ]);

    // Grades.
    Route::get('/courses/{slug}/grades', [
        'as'   => 'courses.grades.index',
        'uses' => 'GradesController@index',
    ]);
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
