<?php

namespace Staticka\Content;

/**
 * TODO: Remove this file after v1.0.0.
 *
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
