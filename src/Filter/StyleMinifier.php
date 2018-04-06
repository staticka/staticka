<?php

namespace Staticka\Filter;

/**
 * Style Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StyleMinifier extends InlineMinifier
{
    /**
     * @var string
     */
    protected $tagname = 'style';
}
