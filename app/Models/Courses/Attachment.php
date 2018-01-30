<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'assignments_attachments';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'assignment_id' => 'required|integer',
        'name'          => 'required',
        'file'          => 'required|unique',
        'mime'          => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
}
