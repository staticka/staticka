<?php

namespace Staticka\Helpers;

use Staticka\Contracts\HelperContract;

/**
 * Link Helper
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LinkHelper implements HelperContract
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
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'url';
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
