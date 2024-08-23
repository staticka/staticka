<?php

namespace Staticka\Contracts;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Site" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface WebsiteContract
{
    /**
     * Add a new page instance in the website.
     *
     * @param \Staticka\Contracts\PageContract $page
     *
     * @return self
     */
    public function add(PageContract $page);

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param string $output
     *
     * @return self
     */
    public function build($output);

    /**
     * Transfers files from a directory into another path.
     *
     * @param string $source
     * @param string $path
     *
     * @return self
     */
    public function copy($source, $path);

    /**
     * @deprecated since ~0.3, use "Builder" instead.
     *
     * Returns the renderer instance.
     *
     * @return \Staticka\Contracts\RendererContract
     */
    public function renderer();
}
