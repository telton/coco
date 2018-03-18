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
        'assignment_id'     => 'required|integer',
        'student_id'        => 'required|integer',
        'grader_id'         => 'required|integer',
        'points_earned'     => 'required|decimal',
        'grade'             => 'required|decimal',
        'letter_grade'      => 'required|string|max:5',
        'comments'          => 'required|string',
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
        'points_earned',
        'grade',
        'letter_grade',
        'comments',
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
