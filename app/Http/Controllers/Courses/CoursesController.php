<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Course;
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
     * Display the specified resource.
     *
     * @param  \App\Models\Courses\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        return view('courses.show', [
            'course' => $course,
        ]);
    }
}
