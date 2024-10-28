<?php

namespace Staticka;

use Rougin\Staticka\Parser;
use Staticka\Contracts\BuilderContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;

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

        /** @deprecated since ~0.3, use DATA_BODY instead */
        $data['content'] = $data[PageContract::DATA_BODY];

        // Try to guess the title from the content ------------
        $title = PageContract::DATA_TITLE;

        if (! isset($data[$title]))
        {
            $output = $data[PageContract::DATA_BODY];

            preg_match('/<h1>(.*?)<\/h1>/', $output, $matches);

            if (isset($matches[1]))
            {
                $data[$title] = $matches[1];
            }
        }
        // ----------------------------------------------------

        $plate = PageContract::DATA_PLATE;

        if ($this->render && isset($data[$plate]))
        {
            $html = $this->render->render($data[$plate], $data);
        }
        else
        {
            $body = $data[PageContract::DATA_BODY];

            $base = $page->layout()->body();

            $search = LayoutContract::BODY_DEFAULT;

            $html = str_replace($search, $body, $base);
        }

        // Apply filters to HTML if applicable ---
        foreach ($page->filters() as $filter)
        {
            $html = $filter->filter($html);
        }
        // ---------------------------------------

        return (string) $html;
    }
}
