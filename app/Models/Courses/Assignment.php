<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Assignment extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'assignments';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'course_id'    => 'required|integer',
        'name'         => 'required',
        'due_date'     => 'required|date',
        'display_date' => 'required|date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'due_date',
        'display_date',
    ];

    /**
     * Cast these dates as an instance of Carbon
     *
     * @var array
     */
    protected $dates = [
        'due_date',
        'display_date',
        'created_at',
        'updated_at',
    ];

    /**
     * Assignment course relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * File attachments
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return mixed
     */
    public function attachments()
    {
        return File::where('assignment_id', $this->attributes['id'])->where('type', 'attachment')->get();
    }

    /**
     * File submissions.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return mixed
     */
    public function submissions()
    {
        return File::where('assignment_id', $this->attributes['id'])->where('type', 'submission')->get();
    }

    /**
     * Get a submission for an assignment.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \App\Models\Courses\File|null
     */
    public function submission()
    {
        return File::where('assignment_id', $this->attributes['id'])->where('type', 'submission')->where('user_id', Auth::user()->id)->first();
    }
}
