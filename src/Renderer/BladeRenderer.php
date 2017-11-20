<?php

namespace Rougin\Staticka\Renderer;

/**
 * Renderer
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BladeRenderer implements \Rougin\Slytherin\Template\RendererInterface
{
    /** 
     * @var \Jenssegers\Blade\Blade
     */
    protected $blade;

    /**
     * Initializes the renderer instance.
     *
     * @param array|string $views
     * @param string       $cache
     */
    public function __construct($views, $cache = null)
    {
        $cache = $cache ?: sys_get_temp_dir();

        $views = (is_string($views)) ? array($views) : $views;

        $this->blade = new \Jenssegers\Blade\Blade($views, $cache);
    }

    /**
     * Returns the string contents of the view.
     *
     * @param  string $view
     * @param  array  $data
     * @return string
     */
    public function render($view, array $data = array())
    {
        return $this->blade->render($view, $data);
    }
}
