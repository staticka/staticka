<?php

namespace Staticka;

use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Page implements PageContract
{
    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @var \Staticka\Contracts\LayoutContract
     */
    protected $layout;

    /**
     * @param \Staticka\Contracts\LayoutContract $layout
     * @param array<string, mixed>               $data
     */
    public function __construct(LayoutContract $layout, $data = array())
    {
        $this->data = (array) $data;

        $this->layout = $layout;

        if (isset($data['filters']))
        {
            /** @var \Staticka\Contracts\FilterContract[] */
            $filters = $data['filters'];

            foreach ($filters as $filter)
            {
                $this->filter($filter);
            }
        }

        if (isset($data['helpers']))
        {
            /** @var \Staticka\Contracts\HelperContract[] */
            $helpers = $data['helpers'];

            foreach ($helpers as $helper)
            {
                $this->helper($helper);
            }
        }
    }

    /**
     * Returns the details of the page instance.
     *
     * @return array<string, mixed>
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Adds a filter instance in the layout.
     *
     * @param \Staticka\Contracts\FilterContract $filter
     *
     * @return self
     */
    public function filter(FilterContract $filter)
    {
        $this->layout->filter($filter);

        return $this;
    }

    /**
     * Returns all available filters.
     *
     * @return \Staticka\Contracts\FilterContract[]
     */
    public function filters()
    {
        return $this->layout->filters();
    }

    /**
     * Adds a helper instance in the layout.
     *
     * @param \Staticka\Contracts\HelperContract $helper
     *
     * @return self
     */
    public function helper(HelperContract $helper)
    {
        $this->layout->helper($helper);

        return $this;
    }

    /**
     * Returns all available helpers.
     *
     * @return \Staticka\Contracts\HelperContract[]
     */
    public function helpers()
    {
        return $this->layout->helpers();
    }

    /**
     * Returns the layout instance.
     *
     * @return \Staticka\Contracts\LayoutContract
     */
    public function layout()
    {
        return $this->layout;
    }
}
