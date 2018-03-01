<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use Carbon\Carbon;

class AssignmentsController extends Controller
{
    /**
     * AssignmentsController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();

        // Make sure only the instructor/admin can access these methods.
        $this->middleware('courses.instructor', [
            'only' => [
                'create',
                'store',
                'edit',
                'update',
                'delete',
            ],
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
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        return view('courses.assignments.index', [
            'course' => $course,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function create(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        $this->breadcrumb->addCrumb('Create', route('courses.assignments.create', $course->slug));
        return view('courses.assignments.create', [
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string                     $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(string $slug, Request $request)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $assignment = Assignment::create([
            'course_id'    => $course->id,
            'name'         => $request->input('name'),
            'description'  => (!is_null($request->input('description'))) ? $request->input('description') : '',
            'due_date'     => new Carbon($request->input('due_date')),
            'display_date' => new Carbon($request->input('display_date')),
        ]);

        $this->flash()->success("The assignment <strong>{$assignment->name}</strong> has been created!");
        return $this->redirect()->route('courses.assignments.show', [$slug, $assignment]);
    }

    /**
     * Display the specified resource.
     *
     * @param string                   $slug
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        $this->breadcrumb->addCrumb($assignment->name, route('courses.assignments.show', [$course->slug, $assignment]));
        return view('courses.assignments.show', [
            'course'     => $course,
            'assignment' => $assignment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string                  $slug
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        $this->breadcrumb->addCrumb($assignment->name, route('courses.assignments.show', [$course->slug, $assignment]));
        $this->breadcrumb->addCrumb('Edit', route('courses.assignments.edit', [$course->slug, $assignment]));
        return view('courses.assignments.edit', [
            'course'     => $course,
            'assignment' => $assignment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $slug
     * @param  \App\Models\Assignment    $assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $assignment->update([
            'course_id'    => $course->id,
            'name'         => $request->input('name'),
            'description'  => $request->input('description'),
            'due_date'     => new Carbon($request->input('due_date')),
            'display_date' => new Carbon($request->input('display_date')),
        ]);

        $assignment->save();

        $this->flash()->success("The assignment <strong>{$assignment->name}</strong> has been updated!");
        return $this->redirect()->route('courses.assignments.show', [$slug, $assignment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
