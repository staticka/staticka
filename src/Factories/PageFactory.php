<?php

namespace Staticka\Factories;

use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Layout;
use Staticka\Matter;
use Staticka\Page;
use Symfony\Component\Yaml\Yaml;

/**
 * Page Factory
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PageFactory
{
    /**
     * @var \Staticka\Contracts\LayoutContract
     */
    protected $layout;

    /**
     * @param \Staticka\Contracts\LayoutContract|null $layout
     */
    public function __construct(LayoutContract $layout = null)
    {
        $this->layout = $layout ? $layout : new Layout;
    }

    /**
     * TODO: To be removed on v1.0.0.
     * Should only be file-based.
     *
     * Creates a new page instance based on the content.
     *
     * @param  string $body
     * @param  array  $data
     * @return \Staticka\Contracts\PageContract
     */
    public function body($body, $data = array())
    {
        $data[PageContract::DATA_BODY] = $body;

        return $this->make((array) $data);
    }

    /**
     * Creates a new page instance based on the file.
     *
     * @param  string $file
     * @param  array  $data
     * @return \Staticka\Contracts\PageContract
     */
    public function file($file, $data = array())
    {
        $result = file_get_contents($file);

        $matter = $this->parse($result);

        $data = array_merge($data, $matter);

        return $this->make((array) $data);
    }

    /**
     * Returns a new page instance.
     *
     * @param  array $data
     * @return \Staticka\Contracts\PageContract
     */
    protected function make($data)
    {
        if (! isset($data[PageContract::DATA_LINK]))
        {
            $data[PageContract::DATA_LINK] = 'index';
        }

        // TODO: Remove this on v1.0.0.
        // Use DATA_PLATE instead of "layout".
        if (isset($data['layout']))
        {
            $data[PageContract::DATA_PLATE] = $data['layout'];
        }

        // TODO: Remove this on v1.0.0.
        // Use DATA_LINK instead of "permalink".
        if (isset($data['permalink']))
        {
            $data[PageContract::DATA_LINK] = $data['permalink'];
        }

        return new Page($this->layout, $data);
    }

    /**
     * Converts the Matter format into data.
     *
     * @param  string $content
     * @return array
     */
    protected function parse($content)
    {
        list($matter, $body) = Matter::parse($content);

        $matter[PageContract::DATA_BODY] = trim($body);

        return (array) $matter;
    }
}
