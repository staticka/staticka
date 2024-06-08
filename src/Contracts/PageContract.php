<?php

namespace Staticka\Contracts;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface PageContract
{
    const DATA_BODY = 'body';

    const DATA_NAME = 'name';

    const DATA_LINK = 'link';

    const DATA_PLATE = 'plate';

    const DATA_TITLE = 'title';

    /**
     * Returns the details of the page instance.
     *
     * @return array<string, mixed>
     */
    public function data();

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

    /**
     * Returns the layout instance.
     *
     * @return \Staticka\Contracts\LayoutContract
     */
    public function layout();
}
