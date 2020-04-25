<?php

namespace Staticka\Contracts;

/**
 * Website Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface WebsiteContract
{
    /**
     * Add a new page instance in the website.
     *
     * @param \Staticka\Contracts\PageContract $page
     */
    public function add(PageContract $page);

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param  string $output
     * @return self
     */
    public function build($output);

    /**
     * Transfers files from a directory into another path.
     *
     * @param  string      $source
     * @param  string|null $path
     * @return void
     */
    public function copy($source, $path);
}
