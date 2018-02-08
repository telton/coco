<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Traits\FlashesSession;
use App\Http\Traits\GivesAuth;
use Illuminate\Container\Container;
use Creitive\Breadcrumbs\Facades\Breadcrumbs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, FlashesSession, GivesAuth;

    /**
     * @type Container
     */
    protected $app;

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumb;

    /**
     * Controller constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        $this->app = Container::getInstance();

        $this->breadcrumb = $this->app->make('breadcrumbs');
        $this->breadcrumb->setListElement('ol');
        $this->breadcrumb->setDivider(null);
        $this->breadcrumb->addCssClasses('breadcrumb');
        $this->breadcrumb->addCrumb('Home', route('home.index'));
    }
}
