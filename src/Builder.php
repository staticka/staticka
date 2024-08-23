<?php

namespace Staticka;

use Rougin\Staticka\Parser;
use Staticka\Contracts\BuilderContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Contracts\RendererContract;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Parser" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Builder extends Parser implements BuilderContract
{
    /**
     * Builds the HTML of the page instance.
     *
     * @param \Staticka\Contracts\PageContract $page
     *
     * @return string
     */
    public function build(PageContract $page)
    {
        $data = array_merge($page->helpers(), $page->data());

        /** @var string */
        $body = $data[PageContract::DATA_BODY];

        $data[PageContract::DATA_BODY] = $this->text($body);

        // TODO: Remove this on v1.0.0.
        // Use DATA_BODY instead of "content".
        $data['content'] = $data[PageContract::DATA_BODY];

        if (! isset($data[PageContract::DATA_TITLE]))
        {
            $output = $data[PageContract::DATA_BODY];

            preg_match('/<h1>(.*?)<\/h1>/', $output, $matches);

            if (isset($matches[1]))
            {
                $data[PageContract::DATA_TITLE] = $matches[1];
            }
        }

        if ($this->render && isset($data[PageContract::DATA_PLATE]))
        {
            $plate = (string) $data[PageContract::DATA_PLATE];

            $html = $this->render->render($plate, $data);
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
}
