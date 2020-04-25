<?php

namespace Staticka\Contracts;

/**
 * Page Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface PageContract
{
    // TODO: Change to "body" in v1.0.0
    const DATA_BODY = 'content';

    const DATA_NAME = 'name';

    // TODO: To be removed in v1.0.0
    // Use DATA_PATH instead
    const DATA_LAYOUT = 'layout';

    // TODO: Change to "link" in v1.0.0
    const DATA_LINK = 'permalink';

    const DATA_PATH = 'path';

    const DATA_TITLE = 'title';

    /**
     * Returns the details of the page instance.
     *
     * @return array
     */
    public function data();

    /**
     * Adds a filter instance in the layout.
     *
     * @param  \Staticka\Contracts\FilterContract $filter
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
     * @param  \Staticka\Contracts\HelperContract $helper
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
