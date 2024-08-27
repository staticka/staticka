<?php

namespace Staticka;

use Rougin\Staticka\Page as Staticka;
use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Page" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Page extends Staticka implements PageContract
{
    /**
     * @param \Staticka\Contracts\LayoutContract $layout
     * @param array<string, mixed>               $data
     */
    public function __construct(LayoutContract $layout, $data = array())
    {
        $this->data = (array) $data;

        /** @var \Rougin\Staticka\Layout $layout */
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
        /** @var \Staticka\Contracts\LayoutContract */
        $layout = $this->layout;

        $layout->filter($filter);

        return $this;
    }

    /**
     * Returns all available filters.
     *
     * @return \Staticka\Contracts\FilterContract[]
     */
    public function filters()
    {
        /** @var \Staticka\Contracts\LayoutContract */
        $layout = $this->layout;

        return $layout->filters();
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
        /** @var \Staticka\Contracts\LayoutContract */
        $layout = $this->layout;

        $layout->helper($helper);

        return $this;
    }

    /**
     * Returns all available helpers.
     *
     * @return \Staticka\Contracts\HelperContract[]
     */
    public function helpers()
    {
        /** @var \Staticka\Contracts\LayoutContract */
        $layout = $this->layout;

        return $layout->helpers();
    }

    /**
     * Returns the layout instance.
     *
     * @return \Staticka\Contracts\LayoutContract
     */
    public function layout()
    {
        /** @var \Staticka\Contracts\LayoutContract */
        return $this->layout;
    }
}
