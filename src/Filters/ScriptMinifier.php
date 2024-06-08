<?php

namespace Staticka\Filters;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param string $code
     *
     * @return string
     */
    protected function minify($code)
    {
        $code = (string) parent::minify($code);

        /** @var string */
        $code = preg_replace('/( )?=( )?/', '=', $code);

        /** @var string */
        $code = preg_replace('/( )?\(( )?/', '(', $code);

        /** @var string */
        return preg_replace('/( )?var ( )?/', '', $code);
    }
}
