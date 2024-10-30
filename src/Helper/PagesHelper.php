<?php

namespace Staticka\Helper;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PagesHelper implements HelperInterface
{
    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * @param \Staticka\Page[] $pages
     */
    public function __construct($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return array<string, mixed>[]
     */
    public function get()
    {
        $result = array();

        foreach ($this->pages as $page)
        {
            $result[] = $page->getData();
        }

        return $result;
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'pages';
    }

    /**
     * @return self
     */
    public function sortDesc()
    {
        $this->pages = array_reverse($this->pages);

        return $this;
    }
}
