<?php

namespace Staticka\Contracts;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Parser" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface BuilderContract
{
    /**
     * Builds the HTML of the page instance.
     *
     * @param \Staticka\Contracts\PageContract $page
     *
     * @return string
     */
    public function build(PageContract $page);
}
