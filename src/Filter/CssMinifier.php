<?php

namespace Rougin\Staticka\Filter;

/**
 * CSS Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CssMinifier extends \MatthiasMullie\Minify\CSS implements FilterInterface
{
    /**
     * Modifies the given code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code)
    {
        $this->add($code);

        return $this->minify();
    }

    /**
     * Renames the filename, if needed.
     *
     * @param  string $filename
     * @return string
     */
    public function rename($filename)
    {
        return str_replace('.css', '.min.css', $filename);
    }

    /**
     * Returns a listing of supported file extensions.
     *
     * @return array
     */
    public function tags()
    {
        return array('css');
    }
}
