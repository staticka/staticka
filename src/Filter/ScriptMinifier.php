<?php

namespace Staticka\Filter;

/**
 * Script Minifier
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
        $code = (string) parent::minify($code);

        $code = preg_replace('/( )?=( )?/', '=', $code);

        $code = preg_replace('/( )?\(( )?/', '(', $code);

        return preg_replace('/( )?var ( )?/', '', $code);
    }
}
