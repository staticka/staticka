<?php

namespace Rougin\Staticka\Content;

/**
 * Content Interface
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ContentInterface
{
    /**
     * Converts the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function convert($code);
}
