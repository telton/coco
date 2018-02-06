<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        return $this->belongsTo(User::class);
    }

    /**
     * Alias to eloquent one-to-many relation's associate() method.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param mixed $instructor
     */
    public function associateInstructor($instructor)
    {
        if (is_object($instructor)) {
            $instructor = $instructor->getKey();
        }

        if (is_array($instructor)) {
            $instructor = $instructor['id'];
        }

        $this->instructor()->associate($instructor);
        $this->save();
    }

    /**
     * Alias to eloquent one-to-many relation's dissociate() method.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param mixed $instructor
     */
    public function dissociateInstructor($instructor)
    {
        if (is_object($instructor)) {
            $instructor = $instructor->getKey();
        }

        if (is_array($instructor)) {
            $instructor = $instructor['id'];
        }

        $this->instructor()->dissociate($instructor);
        $this->save();
    }
}
