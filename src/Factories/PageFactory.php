<?php

namespace Staticka\Factories;

use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Layout;
use Staticka\Matter;
use Staticka\Page;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
        $this->layout = new Layout;

        if ($layout)
        {
            $this->layout = $layout;
        }
    }

    /**
     * @deprecated since ~0.3, should only be file-based.
     *
     * Creates a new page instance based on the content.
     *
     * @param string               $body
     * @param array<string, mixed> $data
     *
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
     * @param string               $file
     * @param array<string, mixed> $data
     *
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
     * @param array<string, mixed> $data
     *
     * @return \Staticka\Contracts\PageContract
     */
    protected function make($data)
    {
        if (! isset($data[PageContract::DATA_LINK]))
        {
            $data[PageContract::DATA_LINK] = 'index';
        }

        /** @deprecated since ~0.3, use DATA_PLATE instead. */
        if (isset($data['layout']))
        {
            $data[PageContract::DATA_PLATE] = $data['layout'];
        }

        /** @deprecated since ~0.3, use DATA_LINK instead. */
        if (isset($data['permalink']))
        {
            $data[PageContract::DATA_LINK] = $data['permalink'];
        }

        return new Page($this->layout, $data);
    }

    /**
     * Converts the Matter format into data.
     *
     * @param string $content
     *
     * @return array<string, mixed>
     */
    protected function parse($content)
    {
        $result = Matter::parse($content);

        /** @var string */
        $matter = $result[0];

        /** @var string */
        $body = $result[1];

        $matter[PageContract::DATA_BODY] = trim($body);

        return (array) $matter;
    }
}
