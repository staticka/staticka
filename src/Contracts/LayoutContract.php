<?php

namespace Staticka\Contracts;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface LayoutContract
{
    const BODY_DEFAULT = '$_CONTENT';

    const TYPE_FILE = 0;

    const TYPE_HTML = 1;

    /**
     * Returns the body content of the layout if available.
     *
     * @return string
     */
    public function body();

    /**
     * Adds a filter instance in the layout.
     *
     * @param \Staticka\Contracts\FilterContract $filter
     *
     * @return self
     */
    public function filter(FilterContract $filter);

    /**
     * Returns all available filters.
     *
     * @return \Staticka\Contracts\FilterContract[]
     */
    public function filters();

    /**
     * Adds a helper instance in the layout.
     *
     * @param \Staticka\Contracts\HelperContract $helper
     *
     * @return self
     */
    public function helper(HelperContract $helper);

    /**
     * Returns all available helpers.
     *
     * @return \Staticka\Contracts\HelperContract[]
     */
    public function helpers();
}
