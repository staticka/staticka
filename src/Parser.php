<?php

namespace Staticka;

use Staticka\Filter\FilterInterface;
use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Parser extends \Parsedown
{
    /**
     * @var \Staticka\Filter\FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var \Staticka\Render\RenderInterface|null
     */
    protected $render = null;

    /**
     * @param \Staticka\Render\RenderInterface|null $render
     */
    public function __construct(RenderInterface $render = null)
    {
        $this->render = $render;
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
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    public function parsePage(Page $page)
    {
        $file = $page->getFile();

        if ($file)
        {
            /** @var string */
            $body = file_get_contents($file);

            $page->setBody($body);
        }

        // Add timestamp if filename format is valid --------
        $data = $page->getData();

        if ($file && ! array_key_exists('created_at', $data))
        {
            $timestamp = $this->getTimestamp($file);

            $data['created_at'] = $timestamp;

            $page = $page->setData($data);
        }
        // --------------------------------------------------

        $page = $this->mergeMatter($page);

        $layout = $page->getLayout();

        $page = $this->parseBody($page);

        if (! $this->render || ! $layout)
        {
            return $page;
        }

        $data = $page->getData();

        $data = $this->insertHelpers($page, $data);

        if ($name = $layout->getName())
        {
            $html = $this->render->render($name, $data);

            $page = $page->setHtml($html);
        }

        return $this->filterPage($page);
    }

    /**
     * @param \Staticka\Render\RenderInterface $render
     *
     * @return self
     */
    public function setRender(RenderInterface $render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    protected function filterPage(Page $page)
    {
        $layout = $page->getLayout();

        if ($layout)
        {
            $filters = $layout->getFilters();

            $html = $page->getHtml();

            foreach ($filters as $filter)
            {
                $html = $filter->filter($html);
            }

            $page->setHtml($html);
        }

        return $page;
    }

    /**
     * @param string $file
     *
     * @return integer|null
     */
    protected function getTimestamp($file)
    {
        $filename = basename($file);

        $timestamp = substr($filename, 0, 14);

        $intStamp = (int) $timestamp;

        $valid = $intStamp <= PHP_INT_MAX
            && $intStamp >= ~PHP_INT_MAX;

        /** @var integer|null */
        return $valid ? strtotime($timestamp) : null;
    }

    /**
     * @param \Staticka\Page       $page
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function insertHelpers(Page $page, $data)
    {
        $layout = $page->getLayout();

        if ($layout)
        {
            $helpers = $layout->getHelpers();

            foreach ($helpers as $helper)
            {
                $data[$helper->name()] = $helper;
            }
        }

        return $data;
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    protected function mergeMatter(Page $page)
    {
        $data = $page->getData();

        $parsed = Matter::parse($page->getBody());

        /** @var array<string, mixed> */
        $matter = $parsed[0];

        $data = array_merge($data, $matter);

        $page->setData($data);

        if (array_key_exists('link', $data))
        {
            /** @var string */
            $link = $data['link'];
            $page->setLink($link);
        }

        if (array_key_exists('name', $data))
        {
            /** @var string */
            $name = $data['name'];
            $page->setName($name);
        }

        if (array_key_exists('plate', $data))
        {
            $layout = $page->getLayout();

            /** @var string */
            $name = $data['plate'];

            if (! $layout)
            {
                $layout = new Layout;
            }

            if (class_exists($name))
            {
                // TODO: Use ContainerInterface for classes ---
                $class = new \ReflectionClass($name);

                /** @var \Staticka\Layout */
                $layout = $class->newInstanceArgs(array());
                // --------------------------------------------
            }
            else
            {
                $layout->setName($name);
            }

            $page->setLayout($layout);
        }

        /** @var string */
        $body = $parsed[1];

        return $page->setBody($body);
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    protected function parseBody(Page $page)
    {
        $body = $page->getBody();

        /** @var array<string, string> */
        $data = $page->getData();

        // Converts placeholder in body, if any -----
        foreach ($data as $key => $value)
        {
            $key = '{' . strtoupper($key) . '}';

            $body = str_replace($key, $value, $body);
        }
        // ------------------------------------------

        // Modify the body with filters prior parsing ---
        foreach ($this->filters as $filter)
        {
            $body = $filter->filter($body);
        }
        // ----------------------------------------------

        $html = $this->parse($body);

        return $page->setHtml($html);
    }
}
