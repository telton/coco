<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoursesController extends Controller
{
    /**
     * CoursesController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        return view('courses.show', [
            'course' => $course,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
