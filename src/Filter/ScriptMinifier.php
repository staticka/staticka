<?php

namespace Staticka\Filter;

/**
 * Script Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ScriptMinifier extends InlineMinifier
{
    /**
     * @var string
     */
    protected $tagname = 'script';

    /**
     * Minifies the specified code.
     *
     * @param  string $code
     * @return string
     */
    protected function minify($code)
    {
        $minified = (string) parent::minify($code);

        $minified = preg_replace('/( )?\(( )?/', '(', $minified);

        $minified = preg_replace('/( )?=( )?/', '=', $minified);

        return preg_replace('/( )?var( )?/', '', $minified);
    }
}
