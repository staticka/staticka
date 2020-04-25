<?php

namespace Staticka\Contracts;

/**
 * TODO: Remove this file after v1.0.0.
 *
 * Filter Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface FilterContract
{
    /**
     * Filters the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code);
}
