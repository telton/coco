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
Route::group(['namespace' => 'Courses', 'middleware' => 'auth'], function () {
    Route::resource('courses', 'CoursesController');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
