<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Course extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'courses';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'subject'       => 'required',
        'course_number' => 'required|integer',
        'section'       => 'required|integer',
        'slug'          => 'required|unique:courses',
        'crn'           => 'required|integer|unique:courses',
        'title'         => 'required',
        'capacity'      => 'required|integer',
        'campus'        => 'required|in:Flint,Ann Arbor,Dearborn',
        'credits'       => 'required|integer',
        'semester'      => 'required|in:Spring,Summer,Fall,Winter',
        'year'          => 'required|integer',
        'instructor_id' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'course_number',
        'section',
        'slug',
        'crn',
        'title',
        'capacity',
        'campus',
        'credits',
        'semester',
        'year',
        'instructor_id',
    ];

    /**
     * Cast these dates as an instance of Carbon
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Courses students relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'courses_students', 'course_id', 'user_id');
    }

    /**
     * Courses instructor relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the graders for a course.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function graders()
    {
        return $this->belongsToMany(User::class, 'courses_graders', 'course_id', 'grader_id');
    }

    /**
     * Get the tutors for a course.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tutors()
    {
        return $this->belongsToMany(User::class, 'courses_tutors', 'course_id', 'tutor_id');
    }

    /**
     * Courses assignment relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id')->orderBy('due_date', 'asc');
    }

    /**
     * Get the courses that are past/on the display date.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visibleAssignments()
    {
        $date = Carbon::now();
        return $this->hasMany(Assignment::class, 'course_id')->whereDate('display_date', '<=', $date)->orderBy('due_date', 'asc');
    }
}
