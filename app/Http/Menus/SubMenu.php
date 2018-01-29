<?php

namespace App\Http\Menus;

use Spatie\Menu\Menu;

class SubMenu extends BaseMenu
{
    /**
     * Not really needed...
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    protected function build()
    {
    }

    /**
     * Because this is a sub menu, we want to return the builder.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @return Menu
     */
    public function render(): Menu
    {
        $this->build();
        return $this->builder;
    }
}
