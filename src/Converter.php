<?php

namespace Rougin\Staticka;

/**
 * Converter
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Converter extends \League\CommonMark\CommonMarkConverter
{
    /**
     * Converts CommonMark to HTML.
     *
     * @param  string $commonmark
     * @return string
     */
    public function convert($commonmark)
    {
        return $this->convertToHtml($commonmark);
    }
}
