<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\File;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\Auth;

class AssignmentsController extends Controller
{
    /**
     * Filesystem.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * AssignmentsController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param Factory $storage
     */
    public function __construct(Factory $storage)
    {
        parent::__construct();

        $this->storage = $storage->disk('assignments');

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

        // Upload each file in the submission.
        foreach ($request->file('uploads', []) as $uploads) {
            // Set the path.
            $path = "{$assignment->id}/attachments/";

            // Create a safe name for storing on the server.
            $name = md5($uploads->getClientOriginalName()) . '.' . $uploads->getClientOriginalExtension();

            // Check for collisions.
            if ($this->storage->exists("{$path}{$name}")) {
                $name = mt_rand(0, 1000) . '-' . $name;
            }

            // Create the file.
            $file = File::create([
                'assignment_id' => $assignment->id,
                'user_id'       => Auth::user()->id,
                'name'          => $uploads->getClientOriginalName(),
                'file'          => $path . $name,
                'mime'          => $uploads->getClientMimeType(),
                'type'          => 'attachment',
            ]);

            // Save the attachment.
            $this->storage->put($path . $name, file_get_contents($uploads));
        }

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

        $attachments = $assignment->attachments();

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        $this->breadcrumb->addCrumb($assignment->name, route('courses.assignments.show', [$course->slug, $assignment]));
        return view('courses.assignments.show', [
            'course'      => $course,
            'assignment'  => $assignment,
            'attachments' => $attachments,
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

        $attachments = $assignment->attachments();

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Assignments', route('courses.assignments.index', $course->slug));
        $this->breadcrumb->addCrumb($assignment->name, route('courses.assignments.show', [$course->slug, $assignment]));
        $this->breadcrumb->addCrumb('Edit', route('courses.assignments.edit', [$course->slug, $assignment]));
        return view('courses.assignments.edit', [
            'course'      => $course,
            'assignment'  => $assignment,
            'attachments' => $attachments,
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
     * @param string                   $slug
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $assignmentName = $assignment->name;

        // Attempt to delete the assignment.
        if ($assignment->delete()) {
            $this->flash()->success("The assignment <strong>{$assignmentName}</strong> was successfully deleted!");
            return $this->redirect()->route('courses.assignments.index', $course->slug);
        } else {
            $this->flash()->warning("The assignment <strong>{$assignmentName}</strong> was NOT deleted!");
            return $this->redirect()->route('courses.assignments.index', $course->slug);
        }
    }

    /**
     * Submit an assignment.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string                   $slug
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function submit(string $slug, Assignment $assignment, Request $request)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        // Upload each file in the submission.
        foreach ($request->file('uploads', []) as $uploads) {
            // Set the path.
            $path = "{$assignment->id}/submissions/";

            // Create a safe name for storing on the server.
            $name = md5($uploads->getClientOriginalName()) . '.' . $uploads->getClientOriginalExtension();

            // Check for collisions.
            if ($this->storage->exists("{$path}{$name}")) {
                $name = mt_rand(0, 1000) . '-' . $name;
            }

            // Create the file.
            $file = File::create([
                'assignment_id' => $assignment->id,
                'user_id'       => Auth::user()->id,
                'name'          => $uploads->getClientOriginalName(),
                'file'          => $path . $name,
                'mime'          => $uploads->getClientMimeType(),
                'type'          => 'submission',
                'comments'      => $request->input('comments'),
            ]);

            // Save the attachment.
            $this->storage->put($path . $name, file_get_contents($uploads));
        }

        $this->flash()->success('Your submission has been successfully saved!');
        return $this->redirect()->route('courses.assignments.show', [$slug, $assignment]);
    }
}
