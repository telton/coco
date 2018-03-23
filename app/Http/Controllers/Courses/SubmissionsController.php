<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Submission;
use App\Models\Courses\Assignment;
use App\Models\Courses\Course;
use App\Models\Courses\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\Auth;

class SubmissionsController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * SubmissionsController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param \Illuminate\Contracts\Filesystem\Factory $storage
     */
    public function __construct(Factory $storage)
    {
        parent::__construct();
        // Make sure only the instructor/admin can access these methods.
        $this->middleware('courses.instructor', [
            'only' => [
                'destroy',
            ],
        ]);
        $this->storage = $storage->disk('assignments');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        // Create the assignment submission.
        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'user_id'       => Auth::user()->id,
            'comments'      => $request->input('submitComments'),
        ]);

        // Upload each file in the submission.
        // If no file was uploaded, just create an File and don't upload anything.
        if (!empty($request->file('uploads', []))) {
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
                    'submission_id' => $submission->id,
                ]);

                // Save the attachment.
                $this->storage->put($path . $name, file_get_contents($uploads));
            }
        }

        $this->flash()->success('Your submission has been successfully saved!');
        return $this->redirect()->route('courses.assignments.show', [$slug, $assignment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @oaram                                  $slug
     * @param  \App\Models\Courses\Assignment  $assignment
     * @param  \App\Models\Courses\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug, Assignment $assignment, Submission $submission)
    {
        $user = $submission->user;

        // Delete the attachments first.
        try {
            foreach ($submission->attachments() as $attachment) {
                if ($this->storage->exists($attachment->file)) {
                    $this->storage->delete($attachment->file);
                    $attachment->delete();
                }
            }
        } catch (\Exception $e) {
            $this->flash()->error("An error ocurred: {$e->getMessage()}");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        }

        // Attempt to delete the submission.
        if ($submission->delete()) {
            $this->flash()->success("The submission for the assignment <strong>{$assignment->name}</strong> has been deleted for student <strong>{$user->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        } else {
            $this->flash()->warning("The submission for the assignment <strong>{$assignment->name}</strong> was NOT deleted for student <strong>{$user->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        }
    }
}
