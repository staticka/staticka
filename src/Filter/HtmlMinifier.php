<?php

namespace Rougin\Staticka\Filter;

/**
 * HTML Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HtmlMinifier extends \voku\helper\HtmlMin implements FilterInterface
{
    /**
     * Modifies the given code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code)
    {
        return trim(preg_replace('/\s+/', ' ', $this->minify($code)));
    }

    /**
     * Renames the filename, if needed.
     *
     * @param  string $filename
     * @return string
     */
    public function rename($filename)
    {
        return $filename;
    }

    /**
     * Returns a listing of supported file extensions.
     *
     * @return array
     */
    public function tags()
    {
        return array('html');
    }
}
