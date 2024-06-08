<?php

namespace Staticka\Content;

/**
 * @deprecated since ~0.3, use "Builder" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param string $code
     *
     * @return string
     */
    public function make($code)
    {
        return $this->text($code);
    }
}
