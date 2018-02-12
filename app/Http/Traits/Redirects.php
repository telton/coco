<?php

namespace App\Http\Traits;

use Illuminate\Routing\Redirector;

trait Redirects
{
    /**
     * Get an instance of the redirector
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string|null $to
     * @param int         $status
     * @param array       $headers
     * @param bool        $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        $redirector = app(Redirector::class);
        if (is_null($to)) {
            return $redirector;
        }

        return $redirector->to($to, $status, $headers, $secure);
    }
}
