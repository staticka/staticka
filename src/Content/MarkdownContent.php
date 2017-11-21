<?php

namespace Rougin\Staticka\Content;

/**
 * Markdown Content
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MarkdownContent extends \Parsedown implements ContentInterface
{
    /**
     * Converts the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function convert($code)
    {
        return $this->text($code);
    }
}
