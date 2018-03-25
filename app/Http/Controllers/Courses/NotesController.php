<?php

namespace App\Http\Controllers\Courses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Courses\Note;
use App\Models\Courses\Course;

class NotesController extends Controller
{
    /**
     * NotesController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of all notes.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function index(string $slug)
    {
        $notes = Note::where('user_id', auth()->user()->id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('My Notes', route('courses.notes.index', $course->slug));

        return view('courses.notes.index', [
            'course' => $course,
            'notes'  => $notes,
        ]);
    }

    /**
     * Show the form for creating a new note.
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
        $this->breadcrumb->addCrumb('My Notes', route('courses.notes.index', $course->slug));
        $this->breadcrumb->addCrumb('Create', route('courses.notes.create', $course->slug));

        return view('courses.notes.create', [
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created note in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $slug
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $slug)
    {
        $note = Note::create([
            'user_id' => Auth::user()->id,
            'title'   => $request->input('title'),
            'slug'    => str_slug($request->title) . str_random(10),
            'body'    => $request->input('body'),
        ]);

        $this->flash()->success("The note <strong>{$note->title}</strong> has been created!");
        return $this->redirect()->route('courses.notes.index', $slug);
    }

    /**
     * Display the specified resource.
     *
     * @param string                     $slug
     * @param  \App\Models\Courses\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug, Note $note)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('My Notes', route('courses.notes.index', $course->slug));
        $this->breadcrumb->addCrumb($note->title, route('courses.notes.show', [$course->slug, $note]));
        return view('courses.notes.show', [
            'course'      => $course,
            'note'        => $note,
        ]);
    }

    /**
     * Show the form for editing the specified note.
     *
     * @param  string                    $slug
     * @param  \App\Models\Courses\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug, Note $note)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('My Notes', route('courses.notes.index', $course->slug));
        $this->breadcrumb->addCrumb($note->title, route('courses.notes.show', [$course->slug, $note]));
        $this->breadcrumb->addCrumb('Edit', route('courses.notes.edit', [$course->slug, $note]));

        return view('courses.notes.edit', [
            'course' => $course,
            'note'   => $note,
        ]);
    }

    /**
     * Update the specified note.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $slug
     * @param  \App\Models\Courses\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug, Note $note)
    {
        $note->update([
            'title' => $request->input('title'),
            'body'  => $request->input('body'),
        ]);

        return 'Saved!';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string                     $slug
     * @param  \App\Models\Courses\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug, Note $note)
    {
        // Attempt to delete the note.
        if ($note->delete()) {
            $this->flash()->success("The note <strong>{$note->title}</strong> was successfully deleted!");
            return $this->redirect()->route('courses.notes.index', $slug);
        } else {
            $this->flash()->warning("The assignment <strong>{$note->title}</strong> was NOT deleted!");
            return $this->redirect()->route('courses.notes.index', $slug);
        }
    }
}
