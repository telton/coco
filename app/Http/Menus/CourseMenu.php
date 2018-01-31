<?php

namespace App\Http\Menus;

use Spatie\Menu\Link;

class CourseMenu extends BaseMenu
{
    protected function build($course = null)
    {
        $this->addClass('nav flex-column')
            ->linkIf(!is_null($course), route('courses.show', $course->slug), '<span class="nav-item-addon"><i class="fa fa-home"></i></span>Course Home')
            ->linkIf(!is_null($course), '#', '<span class="nav-item-addon"><i class="fa fa-archive"></i></span>Assignments');

        $this->each(function (Link $link) {
            $link->addParentClass('nav-item');
            $link->addClass('nav-link');
        });
    }
}
