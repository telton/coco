<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'user_id' => 'required|integer',
        'title'   => 'required',
        'slug'    => 'required|unique',
        'body'    => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
