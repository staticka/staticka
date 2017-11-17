<?php

namespace Rougin\Staticka;

/**
 * Route
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Route
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $extension = 'md';

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $view;

    /**
     * Initializes the route instance.
     *
     * @param string $uri
     * @param string $content
     * @param string $view
     */
    public function __construct($uri, $content = null, $view = 'index')
    {
        $this->content = $content ?: $uri;

        $this->uri = $uri;

        $this->view = $view;
    }

    /**
     * Returns the filename of the content.
     *
     * @return string
     */
    public function content()
    {
        return $this->content . '.' . $this->extension;
    }

    /**
     * Returns a listing of URIs or the string one.
     *
     * @param  boolean $array
     * @return array|string
     */
    public function uri($array = false)
    {
        if ($array === true) {
            $items = explode('/', $this->uri);

            $items = array_filter($items);

            return array_values($items);
        }

        return $this->uri;
    }

    /**
     * Returns the view name.
     *
     * @return string
     */
    public function view()
    {
        return $this->view;
    }
}
