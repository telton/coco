<?php

namespace App\Http\Middleware\Courses;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Models\Courses\Course;

class PermissionsCheck
{
    /**
     * @var Guard
     */
    protected $auth;

    protected $request;

    /**
     * PermissionsCheck constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth, Request $request)
    {
        $this->auth = $auth;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Make sure the user is logged in.
        if (!$this->auth->check()) {
            return redirect()->guest('/login');
        }

        // If the user has the proper role to access..
        if (!$this->auth->user()->hasRole(['admin', 'instructor', 'student', 'grader', 'tutor'])) {
            abort(403, 'You do not have access to this resource.');
        }

        $hasAccess = false;

        // Check admin.
        if ($this->auth->user()->hasRole('admin')) {
            $hasAccess = true;
        }

        // If the user is an instructor, make sure they are the instructor for the course.
        if ($this->auth->user()->hasRole('instructor')) {
            $course = Course::where('slug', $this->request->segment(2))->first();

            if (!$course) {
                // If the course was not found, abort with a status of 404.
                abort(404);
            }

            if ($course->instructor_id === $this->auth->user()->id) {
                $hasAccess = true;
            }
        }

        // If the user is a student, make sure they are registered for the course.
        if ($this->auth->user()->hasRole('student')) {
            $course = Course::where('slug', $this->request->segment(2))->first();

            if (!$course) {
                // If the course was not found, abort with a status of 404.
                abort(404);
            }

            foreach ($this->auth->user()->courses as $registeredCourse) {
                if ($registeredCourse->slug === $course->slug) {
                    $hasAccess = true;
                }
            }
        }

        // If the user is a grader, make sure they are assigned to grade the course.
        if ($this->auth->user()->hasRole('grader')) {
            $course = Course::where('slug', $this->request->segment(2))->first();

            if (!$course) {
                // If the course was not found, abort with a status of 404.
                abort(404);
            }

            foreach ($course->graders as $grader) {
                if ($this->auth->user()->id === $grader->id) {
                    $hasAccess = true;
                }
            }
        }

        // TODO: Add checks for tutors, after they've been implemented.

        if (!$hasAccess) {
            abort(403, 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
