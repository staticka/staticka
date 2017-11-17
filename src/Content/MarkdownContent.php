<?php

namespace Rougin\Staticka\Content;

use League\CommonMark\CommonMarkConverter;

/**
 * Markdown Content
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MarkdownContent extends CommonMarkConverter implements ContentInterface
{
    /**
     * Converts the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function convert($code)
    {
        return $this->convertToHtml($code);
    }
}
