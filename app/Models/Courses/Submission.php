<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Submission extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'assignment_submissions';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'assignment_id' => 'required|integer',
        'user_id'       => 'required|integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id',
        'user_id',
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
     * Submission assignment relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Submission user relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * File attachments
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return mixed
     */
    public function attachments()
    {
        return File::where('submission_id', $this->attributes['id'])->where('type', 'submission')->get();
    }

    /**
     * Get the grade associated with the assignment submission.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param $assignmentId
     * @return mixed
     */
    public function grade($assignmentId)
    {
        return Grade::where('assignment_id', $assignmentId)->first();
    }
}
