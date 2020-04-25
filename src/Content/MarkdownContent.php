<?php

namespace Staticka\Content;

/**
 * TODO: Remove this file after v1.0.0.
 *
 * Markdown Content
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
