<?php

namespace App\Http\Menus;

class CourseMenu extends BaseMenu
{
    protected function build()
    {
        $this->addClass('nav flex-column')
            ->link('#', '<span class="nav-item-addon"><i class="fa fa-home"></i></span>Course Home')
            ->link('#', '<span class="nav-item-addon"><i class="fa fa-archive"></i></span>Assignments');
    }
}
