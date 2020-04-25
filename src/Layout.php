<?php

namespace Staticka;

use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;

/**
 * Layout
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Layout implements LayoutContract
{
    /**
     * @var string
     */
    protected $body = self::BODY_DEFAULT;

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * @var array
     */
    protected $helpers = array();

    /**
     * @var string $body
     */
    public function __construct(string $body = self::BODY_DEFAULT)
    {
        $this->body = $body;
    }

    /**
     * Returns the body content of the layout if available.
     *
     * @return string
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Adds a filter instance in the layout.
     *
     * @param  \Staticka\Contracts\FilterContract $filter
     * @return self
     */
    public function filter(FilterContract $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Returns all available filters.
     *
     * @return \Staticka\Contracts\FilterContract[]
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Adds a helper instance in the layout.
     *
     * @param  \Staticka\Contracts\HelperContract $helper
     * @return self
     */
    public function helper(HelperContract $helper)
    {
        $this->helpers[$helper->name()] = $helper;
    }

    /**
     * Returns all available helpers.
     *
     * @return \Staticka\Contracts\HelperContract[]
     */
    public function helpers()
    {
        return $this->helpers;
    }
}
