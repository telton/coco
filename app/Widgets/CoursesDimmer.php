<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;
use App\Models\Courses\Course;

class Courses extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Course::count();

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-archive',
            'title'  => "{$count} Courses",
            'text'   => "You have {$count} Courses in your database. Click on button below to view all courses.",
            'button' => [
                'text' => 'Courses',
                'link' => '/admin/courses/',
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }
}
