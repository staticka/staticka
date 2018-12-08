<?php

namespace Staticka\Content;

/**
 * Content Interface
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface ContentInterface
{
    /**
     * File name extension to be used.
     *
     * @return string
     */
    public function extension();

    /**
     * Converts the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function make($code);
}
