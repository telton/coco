<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Courses\Course;
use TCG\Voyager\Models\Role;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable, SoftDeletes;

    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'users';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast these dates as an instance of Carbon
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Users courses relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'courses_students', 'user_id', 'course_id');
    }

    /**
     * Check to see if a user has a given role.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string|array $name
     * @return bool
     */
    public function hasRole($name)
    {
        // Allow an array of roles to be checked. Saves line space.
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                // If the user has the role, exit out of the loop and return true.
                if ($hasRole) {
                    return true;
                }
            }

            // At this point, we've gone through the entire array and haven't found the role. Return false.
            return false;
        }

        return $this->attributes['role_id'] === Role::where('name', $name)->first()->id;
    }
}
