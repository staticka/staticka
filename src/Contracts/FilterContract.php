<?php

namespace Staticka\Contracts;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface FilterContract
{
    /**
     * Filters the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    public function filter($code);
}
