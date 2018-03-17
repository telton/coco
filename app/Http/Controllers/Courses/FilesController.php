<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\Factory;
use App\Models\Courses\File;
use App\Models\Courses\Assignment;

class FilesController extends Controller
{
    /**
     * Filesystem.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * FilesController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param Factory $storage
     */
    public function __construct(Factory $storage)
    {
        parent::__construct();

        $this->storage = $storage->disk('assignments');
    }

    /**
     * Display the specified resource.
     *
     * @param string                   $slug
     * @param  \App\Models\Assignment  $assignment
     * @param                          $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug, Assignment $assignment, $id)
    {
        $file = File::where('id', $id)->first();

        // If the file was not found, abort with a status of 404.
        if (!$file) {
            abort(404);
        }

        return response()->stream(function () use ($file) {
            echo $this->storage->get($file->file);
        }, 200, [
            'Content-Type'        => $file->mime,
            'Content-Disposition' => "inline; filename={$file->name}",
            'size'                => $this->storage->size($file->file),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string                           $slug
     * @param  \App\Models\Courses\Assignment  $assignment
     * @param                                  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug, Assignment $assignment, $id)
    {
        $file = File::where('id', $id)->first();
        $user = $file->user;

        // Attempt to delete the file.
        if ($file->delete()) {
            $this->flash()->success("The submission for the assignment <strong>{$assignment->name}</strong> has been deleted for student <strong>{$user->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        } else {
            $this->flash()->warning("The submission for the assignment <strong>{$assignment->name}</strong> was NOT deleted for student <strong>{$user->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        }
    }
}
