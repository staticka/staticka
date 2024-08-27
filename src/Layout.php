<?php

namespace Staticka;

use Rougin\Staticka\Layout as Staticka;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Layout" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Layout extends Staticka implements LayoutContract
{
    /**
     * @var string
     */
    protected $body = self::BODY_DEFAULT;

    /**
     * @param string $body
     */
    public function __construct($body = self::BODY_DEFAULT)
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
     * @param \Staticka\Contracts\FilterContract $filter
     *
     * @return self
     */
    public function filter(FilterContract $filter)
    {
        $this->addFilter($filter);

        return $this;
    }

    /**
     * Returns all available filters.
     *
     * @return \Staticka\Contracts\FilterContract[]
     */
    public function filters()
    {
        /** @var \Staticka\Contracts\FilterContract[] */
        return $this->getFilters();
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
        $this->addHelper($helper);

        return $this;
    }

    /**
     * Returns all available helpers.
     *
     * @return array<string, \Staticka\Contracts\HelperContract>
     */
    public function helpers()
    {
        $items = $this->getHelpers();

        $result = array();

        /** @var \Staticka\Contracts\HelperContract $item */
        foreach ($items as $item)
        {
            $result[$item->name()] = $item;
        }

        return $result;
    }
}
