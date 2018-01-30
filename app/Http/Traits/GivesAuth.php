<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Auth\Guard;

trait GivesAuth
{
    /**
     * Gives the auth instance.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @return Guard
     */
    public function auth()
    {
        return app(Guard::class);
    }
}
