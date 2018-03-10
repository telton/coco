<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Courses\Course;

class GradesController extends Controller
{
    /**
     * GradesController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('courses.grades')
            ->only([
                'dashboard',
                'store',
            ]);
    }

    /**
     * Grades dashboard for admins/instructors/graders.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string $slug
     * @returns \Illuminate\Http\Response
     */
    public function dashboard(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $submissions = [];
        $assignments = [
            'ungraded'   => [],
            'unapproved' => [],
            'completed'  => [],
        ];

        foreach ($course->assignments as $assignment) {
            foreach ($assignment->submissions() as $submission) {
                // If the assignment submission does not have a grade yet,
                // add it to the ungraded index of the $submissions array.
                // If the assignment submission has a grade, but is not approved,
                // add it to the unapproved index of the $submissions array.
                // Otherwise, that means that there is a grade for that submission and it is approved.
                // Add that submission to the completed index of the $submissions array.
                if (!$submission->grade($assignment->id)) {
                    $submissions['ungraded'][] = $submission;

                    if (!in_array($assignment, $assignments)) {
                        $assignments['ungraded'][] = $assignment;
                    }
                } elseif (!$submission->grade($assignment->id)->approved) {
                    $submissions['unapproved'][] = $submission;

                    if (!in_array($assignment, $assignments)) {
                        $assignments['unapproved'][] = $assignment;
                    }
                } else {
                    $submissions['completed'][] = $submission;

                    if (!in_array($assignment, $assignments)) {
                        $assignments['completed'][] = $assignment;
                    }
                }
            }
        }

        return view('courses.grades.dashboard', [
            'course'                => $course,
            'assignments'           => $assignments,
            'submissions'           => $submissions,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function index(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Grades', route('courses.grades.index', $course->slug));
        return view('courses.grades.index', [
            'course' => $course,
        ]);
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
     * @param  \App\Models\Courses\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        //
    }
}
