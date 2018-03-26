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
        'as'   => 'courses.assignments.submissions.store',
        'uses' => 'SubmissionsController@store',
    ]);

    // Attachments.
    Route::get('/courses/{slug}/assignments/{assignment}/attachments/{attachment}', [
        'as'   => 'courses.assignments.attachments.show',
        'uses' => 'FilesController@show',
    ]);
    Route::post('/courses/{slug}/assignments/{assignment}/attachments/{attachment}/delete', [
        'as'   => 'courses.assignments.attachments.destroy',
        'uses' => 'FilesController@destroy',
    ]);

    // Grades.
    Route::get('/courses/{slug}/grades', [
        'as'   => 'courses.grades.index',
        'uses' => 'GradesController@index',
    ]);
    Route::get('/courses/{slug}/grades/export', [
        'as'   => 'courses.grades.export',
        'uses' => 'GradesController@studentExport',
    ]);
    Route::get('/courses/{slug}/grades/dashboard', [
        'as'   => 'courses.grades.dashboard',
        'uses' => 'GradesController@dashboard',
    ]);
    Route::post('/courses/{slug}/grades/assignments/{assignment}', [
        'as'   => 'courses.grades.store',
        'uses' => 'GradesController@store',
    ]);
    Route::post('/courses/{slug}/grades/assignments/{assignment}/grades/{grade}', [
        'as'   => 'courses.grades.update',
        'uses' => 'GradesController@update',
    ]);
    Route::post('/courses/{slug}/grades/assignments/{assignment}/grades/{grade}/delete', [
        'as'   => 'courses.grades.destroy',
        'uses' => 'GradesController@destroy',
    ]);
    Route::get('/courses/{slug}/grades/assignment/{assignment}/grades/export', [
        'as'   => 'coruses.assignments.grades.export',
        'uses' => 'GradesController@assignmentExport',
    ]);
    Route::get('/courses/{slug}/grades/dashboard/export', [
        'as'   => 'courses.grades.dashboard.export',
        'uses' => 'GradesController@dashboardExport',
    ]);

    // Submissions.
    Route::post('/courses/{slug}/assignments/{assignment}/submissions/{submission}/delete', [
        'as'   => 'courses.assignments.submissions.destroy',
        'uses' => 'SubmissionsController@destroy',
    ]);

    // Notes.
    Route::get('/courses/{slug}/notes', [
        'as'   => 'courses.notes.index',
        'uses' => 'NotesController@index',
    ]);
    Route::get('/courses/{slug}/notes/create', [
        'as'   => 'courses.notes.create',
        'uses' => 'NotesController@create',
    ]);
    Route::post('/courses/{slug}/notes', [
        'as'   => 'courses.notes.store',
        'uses' => 'NotesController@store',
    ]);
    Route::get('/courses/{slug}/notes/{note}', [
        'as'   => 'courses.notes.show',
        'uses' => 'NotesController@show',
    ]);
    Route::get('/courses/{slug}/notes/{note}/edit', [
        'as'   => 'courses.notes.edit',
        'uses' => 'NotesController@edit',
    ]);
    Route::patch('/courses/{slug}/notes/{note}/edit', [
        'as'   => 'courses.notes.update',
        'uses' => 'NotesController@update',
    ]);
    Route::post('/courses/{slug}/notes/{note}/delete', [
        'as'   => 'courses.notes.destroy',
        'uses' => 'NotesController@destroy',
    ]);
    Route::get('/courses/{slug}/notes/{note}/export', [
        'as'   => 'courses.notes.export',
        'uses' => 'NotesController@export',
    ]);
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
