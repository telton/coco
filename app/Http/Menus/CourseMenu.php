<?php

namespace App\Http\Menus;

use Spatie\Menu\Link;

class CourseMenu extends BaseMenu
{
    protected function build($course = null)
    {
        $this->addClass('nav flex-column')
            ->linkIf(!is_null($course), route('courses.show', $course->slug), '<span class="nav-item-addon"><i class="fa fa-home"></i></span>Course Home')
            ->linkIf(!is_null($course), route('courses.assignments.index', $course->slug), '<span class="nav-item-addon"><i class="fa fa-archive"></i></span>Assignments')
            ->linkIf(!is_null($course), route('courses.notes.index', $course->slug), '<span class="nav-item-addon"><i class="fa fa-edit"></i></span>My Notes')
            ->linkIf((!is_null($course) && \Illuminate\Support\Facades\Auth::user()->hasRole('student')), route('courses.grades.index', $course->slug), '<span class="nav-item-addon"><i class="fa fa-calendar-check-o"></i></span>My Grades')
            ->linkIf((!is_null($course) && \Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'instructor', 'grader'])), route('courses.grades.dashboard', $course->slug), '<span class="nav-item-addon"><i class="fa fa-calendar-check-o"></i></span>Grades');

        $this->each(function (Link $link) {
            $link->addParentClass('nav-item');
            $link->addClass('nav-link');
        });
    }
}
