<?php

namespace Staticka\Filter;

/**
 * Filter Interface
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface FilterInterface
{
    /**
     * Filters the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code);
}