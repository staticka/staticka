<?php

namespace Staticka\Contracts;

/**
 * Builder Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface BuilderContract
{
    /**
     * Builds the HTML of the page instance.
     *
     * @param  \Staticka\Contracts\PageContract $page
     * @return string
     */
    public function build(PageContract $page);
}
