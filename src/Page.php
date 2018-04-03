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
     * Initializes the page instance.
     *
     * @param string $file
     * @param array  $data
     */
    public function __construct($file, array $data = array())
    {
        $this->content = (string) $file;

        $this->data = (array) $data;

        if (file_exists($file) === true) {
            $original = (string) file_get_contents($file);

            $this->uri = pathinfo($file, 8);

            list($matter, $content) = Matter::parse($original);

            $this->content = (string) $content;

            $this->data = array_merge($data, $matter);
        }
    }

    /**
     * Returns the page content.
     *
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Returns the data to be inserted in the layout.
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
        $uri = (string) $this->uri;

        $exists = isset($this->data['permalink']);

        $exists && $uri = $this->data['permalink'];

        $items = explode('/', (string) $uri);

        $items = array_filter((array) $items);

        return (array) array_values($items);
    }

    /**
     * Returns the layout of the page.
     *
     * @return string|null
     */
    public function layout()
    {
        $exists = (boolean) isset($this->data['layout']);

        return $exists ? $this->data['layout'] : null;
    }
}
