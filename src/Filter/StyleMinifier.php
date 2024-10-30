<?php

namespace Staticka\Filter;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StyleMinifier extends InlineMinifier
{
    /**
     * @var string
     */
    protected $tagname = 'style';

    /**
     * Minifies the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    protected function minify($code)
    {
        $minified = parent::minify($code);

        return str_replace(' > ', '>', $minified);
    }
}
