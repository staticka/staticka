<?php

namespace Rougin\Staticka\Helper;

/**
 * URL Helper
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class UrlHelper
{
    /**
     * @var string
     */
    protected $base;

    /**
     * Initializes the URL instance.
     *
     * @param string $base
     */
    public function __construct($base)
    {
        $this->base = $base;
    }

    /**
     * Sets the specified link.
     *
     * @param  string $link
     * @return string
     */
    public function set($link)
    {
        $link = $link[0] !== '/' ? '/' . $link : $link;

        return $this->base . $link;
    }
}
