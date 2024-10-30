<?php

namespace Staticka;

use Staticka\Filter\FilterInterface;
use Staticka\Helper\HelperInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Layout
{
    /**
     * @var \Staticka\Filter\FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var \Staticka\Helper\HelperInterface[]
     */
    protected $helpers = array();

    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @return \Staticka\Filter\FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return \Staticka\Helper\HelperInterface[]
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Staticka\Filter\FilterInterface $filter
     *
     * @return self
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param \Staticka\Helper\HelperInterface $helper
     *
     * @return self
     */
    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
