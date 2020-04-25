<?php

namespace Staticka;

use Staticka\Contracts\BuilderContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Contracts\RendererContract;

/**
 * Builder
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder extends \Parsedown implements BuilderContract
{
    /**
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * @param \Staticka\Contracts\RendererContract $renderer
     */
    public function __construct(RendererContract $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Builds the HTML of the page instance.
     *
     * @param  \Staticka\Contracts\PageContract $page
     * @return string
     */
    public function build(PageContract $page)
    {
        $data = array_merge($page->helpers(), $page->data());

        $body = (string) $data[PageContract::DATA_BODY];

        $data[PageContract::DATA_BODY] = $this->text($body);

        if (! isset($data[PageContract::DATA_TITLE]))
        {
            $output = $data[PageContract::DATA_BODY];

            preg_match('/<h1>(.*?)<\/h1>/', $output, $matches);

            if (isset($matches[1]))
            {
                $data[PageContract::DATA_TITLE] = $matches[1];
            }
        }

        if (isset($data[PageContract::DATA_PATH]))
        {
            $path = (string) $data[PageContract::DATA_PATH];

            $html = $this->renderer->render($path, $data);
        }
        else
        {
            $body = $data[PageContract::DATA_BODY];

            $base = $page->layout()->body();

            $search = LayoutContract::BODY_DEFAULT;

            $html = str_replace($search, $body, $base);
        }

        foreach ($page->filters() as $filter)
        {
            $html = $filter->filter($html);
        }

        return (string) $html;
    }

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Returns the renderer instance.
     *
     * @return \Zapheus\Renderer\RendererInterface
     */
    public function renderer()
    {
        return $this->renderer;
    }
}