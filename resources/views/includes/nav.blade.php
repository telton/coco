<div class="main-nav">
    <div class="nav-frame">
        <div class="nav-header">
            <button class="main-nav-toggle" type="button" data-toggle="collapse" data-target="#nav-collapse">
                <span class="main-nav-toggle-addon"><i class="fa fa-bars"></i></span>Menu
            </button>
        </div>
        <div class="nav-body collapse" id="nav-collapse">
            @inject('courseMenu', 'App\Http\Menus\CourseMenu')
            {!! $courseMenu->render($course) !!}
        </div>
    </div>
</div>