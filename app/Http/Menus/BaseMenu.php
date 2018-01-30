<?php

namespace App\Http\Menus;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Spatie\Menu\Menu;
use Spatie\Menu\Item;
use Spatie\Menu\Link;

abstract class BaseMenu
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Menu
     */
    protected $builder;

    /**
     * BaseMenu constructor.
     *
     * @param Guard   $auth
     * @param Request $request
     */
    public function __construct(Guard $auth, Request $request)
    {
        $this->auth = $auth;
        $this->request = $request;
        $this->builder = Menu::new();
    }

    /**
     * Build the menu in this function.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @return mixed
     */
    abstract protected function build();

    /**
     * Add a class to the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param $class
     *
     * @return $this
     */
    public function addClass($class)
    {
        $this->builder->addClass($class);
        return $this;
    }

    /**
     * Wrap the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param string $element
     * @param array  $attributes
     *
     * @return $this
     */
    public function wrap(string $element, $attributes = [])
    {
        $this->builder->wrap($element, $attributes);
        return $this;
    }

    /**
     * Add an item to the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param Item $item
     *
     * @return $this
     */
    public function add(Item $item)
    {
        $this->builder->add($item);
        return $this;
    }

    /**
     * Conditionally add an item to the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param bool $condition
     * @param Item $item
     *
     * @return $this
     */
    public function addIf($condition, Item $item)
    {
        $this->builder->addIf($condition, $item);
        return $this;
    }

    /**
     * Add a plain link to the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param string $url
     * @param string $text
     *
     * #return $this
     */
    public function link(string $url, string $text)
    {
        $this->builder->link($url, $text);
        return $this;
    }

    /**
     * Conditionally add a plain link to the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param bool   $condition
     * @param string $url
     * @param string $text
     *
     * @return $this
     */
    public function linkIf($condition, string $url, string $text)
    {
        $this->builder->linkIf($condition, $url, $text);
        return $this;
    }

    /**
     * Create a submenu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param \Closure $closure
     *
     * @return $this
     */
    public function subMenu(\Closure $closure)
    {
        $subMenu = new SubMenu($this->auth, $this->request);
        $closure($subMenu);

        $this->builder->submenu($subMenu);
        return $this;
    }

    /**
     * Conditionally create a submenu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param          $condition
     * @param \Closure $closure
     *
     * @return $this
     */
    public function subMenuIf($condition, \Closure $closure)
    {
        if ($this->resolveCondition($condition)) {
            $this->subMenu($closure);
        }

        return $this;
    }

    /**
     * Create a bootstrap dropdown menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param string   $text
     * @param \Closure $closure
     *
     * @return $this
     */
    public function bootstrapSubMenu(string $text, \Closure $closure)
    {
        $subMenu = new SubMenu($this->auth, $this->request);
        $closure($subMenu);

        $this->builder->submenu(
            Link::to('#', '{$text} <span class=\"caret\"></span>')
                ->addClass('dropdown-toggle')
                ->setAttributes(['data-toggle' => 'dropdown', 'role' => 'button']),
            $subMenu->addClass('dropdown-menu')
                ->render()
        );

        return $this;
    }

    /**
     * Conditionally create a bootstrap dropdown menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param          $condition
     * @param string   $text
     * @param \Closure $closure
     *
     * @return $this
     */
    public function bootstrapSubMenuIf($condition, string $text, \Closure $closure)
    {
        if ($this->resolveCondition($condition)) {
            $this->bootstrapSubMenu($text, $closure);
        }

        return $this;
    }

    /**
     * Iterate over all the items and apply a callback. If you typehint the
     * item parameter in the callable, it will only be applied to items of that
     * type.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function each(callable $callable)
    {
        $this->builder->each($callable);

        return $this;
    }

    /**
     * Resolve a condition.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @param $conditonal
     *
     * @return bool
     */
    protected function resolveCondition($conditional)
    {
        return is_callable($conditional) ? $conditional() : $conditional;
    }

    /**
     * Render the menu.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @return string
     */
    public function render($data = null)
    {
        $this->build($data);
        $this->builder->setActiveFromUrl($this->request->getUri());

        return $this->builder->render();
    }

    /**
     * Render the menu if it is echoed out.
     *
     * @author Tyler Elton <telton@umflint.edu>
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
