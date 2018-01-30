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
     * Courses users relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'courses_users', 'course_id', 'user_id');
    }
}
