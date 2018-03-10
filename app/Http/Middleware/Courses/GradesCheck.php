<?php

namespace App\Http\Middleware\Courses;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class GradesCheck
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * GradesCheck constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
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

        // If the user is not an admin, instructor or grader, don't allow them access.
        if (!$this->auth->user()->hasRole(['admin', 'instructor', 'grader'])) {
            abort(403, 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
