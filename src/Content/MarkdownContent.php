<?php

namespace Staticka\Content;

/**
 * Markdown Content
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MarkdownContent extends \Parsedown implements ContentInterface
{
    /**
     * File name extension to be used.
     *
     * @return string
     */
    public function extension()
    {
        return 'md';
    }

    /**
     * Converts the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function make($code)
    {
        return $this->text($code);
    }
}
