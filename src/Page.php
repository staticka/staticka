<?php

namespace Staticka;

/**
 * Page
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Page
{
    /**
     * @var string|callable
     */
    protected $content;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string|null
     */
    protected $template;

    /**
     * Initializes the page instance.
     *
     * @param string          $uri
     * @param string|callable $content
     * @param string|null     $template
     * @param array           $data
     */
    public function __construct($uri, $content, $template = null, array $data = array())
    {
        isset($data['permalink']) && $uri = $data['permalink'];

        $this->data = (array) $data;

        $this->uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        $this->content = $content;

        isset($data['layout']) && $template = $data['layout'];

        $this->template = $template;
    }

    /**
     * Returns the page content.
     *
     * @return string
     */
    public function content()
    {
        $content = $this->content;

        is_callable($content) && $content = $content();

        if (is_string($content) && file_exists($content)) {
            $content = (string) file_get_contents($content);
        }

        return $content;
    }

    /**
     * Returns the data for the template.
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Returns an array of URI segments.
     *
     * @return array
     */
    public function uris()
    {
        $items = explode('/', $this->uri);

        $items = array_filter((array) $items);

        return (array) array_values($items);
    }

    /**
     * Returns the template of the page.
     *
     * @return string|null
     */
    public function template()
    {
        return $this->template;
    }
}
