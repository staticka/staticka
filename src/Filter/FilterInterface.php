<?php

namespace Rougin\Staticka\Filter;

/**
 * Filter Interface
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface FilterInterface
{
    /**
     * Modifies the given code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code);

    /**
     * Renames the filename, if needed.
     *
     * @param  string $filename
     * @return string
     */
    public function rename($filename);

    /**
     * Returns a listing of supported file extensions.
     *
     * @return array
     */
    public function tags();
}