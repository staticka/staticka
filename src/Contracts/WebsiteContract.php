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
     * @return self
     */
    public function copy($source, $path);

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Returns the renderer instance.
     *
     * @return \Staticka\Contracts\RendererContract
     */
    public function renderer();
}
