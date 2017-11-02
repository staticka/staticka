<?php

namespace Rougin\Staticka;

use Illuminate\Contracts\Container\Container;

/**
 * Renderer
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Renderer extends \Jenssegers\Blade\Blade
{
    /**
     * Initializes the renderer instance.
     *
     * @param array|string $views
     * @param string       $cache
     */
    public function __construct($views, $cache = null)
    {
        $views = (is_string($views)) ? array($views) : $views;

        parent::__construct($views, $cache ?: sys_get_temp_dir());
    }

    /**
     * Returns the string contents of the view.
     *
     * @param  string $view
     * @param  array  $data
     * @param  array  $merge
     * @return string
     */
    public function render($view, $data = array(), $merge = array())
    {
        return parent::render($view, $data, $merge);
    }
}
