<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Grade extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'grades';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'assignment_id'    => 'required|integer',
        'student_id'       => 'required|integer',
        'grader_id'        => 'required|integer',
        'grade'            => 'required|float',
        'letter_grade'     => 'required|string|max:5',
        'approved'         => 'required|boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id',
        'student_id',
        'grader_id',
        'grade',
        'letter_grade',
        'approved',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'approved' => 'boolean',
    ];

    /**
     * Helper function to determine if a grade has been approved.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return bool
     */
    public function isApproved()
    {
        return $this->attributes['approved'];
    }

    /**
     * Get the assignment.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    /**
     * Get the student.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the grader.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grader()
    {
        return $this->belongsTo(User::class, 'grader_id');
    }
}