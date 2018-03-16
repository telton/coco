<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class File extends Model
{
    /**
     * Table used by the model.
     *
     * @var array
     */
    protected $table = 'assignments_files';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'assignment_id' => 'required|integer',
        'user_id'       => 'required|integer',
        'name'          => 'required',
        'file'          => 'unique',
        'type'          => 'required|in:attachment,submission',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id',
        'user_id',
        'name',
        'file',
        'mime',
        'type',
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
     * The allowed mimes.
     *
     * @var array
     */
    public static $mimes = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/psd',
        'image/bmp',
        'image/tiff',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/octet-stream',
        'application/excel',
        'application/x-excel',
        'application/x-msexcel',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-office',
        'application/pdf',
        'application/zip',
        'application/x-compressed-zip',
        'text/plain',
        'text/richtext',
        'application/x-rtf',
        'application/rtf',
        'text/rtf',
        'text/html',
    ];

    /**
     * Attachment constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rules['mime'] = '|in:' . implode(',', self::$mimes);
    }

    /**
     * Get the icon for this file type.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return string
     */
    public function getIconAttribute()
    {
        switch ($this->attributes['mime']) {
            case 'image/png':
            case 'image/jpeg':
            case 'image/gif':
            case 'image/psd':
            case 'image/bmp':
            case 'image/tiff':
                return 'fa-file-image-o';

            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'fa-file-word-o';

            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                return 'fa-file-powerpoint-o';

            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'fa-file-excel-o';

            case 'application/pdf':
                return 'fa-file-pdf-o';

            case 'application/zip':
            case 'application/x-compressed-zip':
                return 'fa-file-zip-o';

            default:
                return 'fa-file-o';
        }
    }

    /**
     * File assignment relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * File user relationship.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
