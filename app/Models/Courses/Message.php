<?php

namespace App\Models\Courses;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'messages';

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'channel',
    ];

    /**
     * Messages user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
