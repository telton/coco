<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;

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
        'name'     => 'required',
        'due_date' => 'required|date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'due_date',
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
