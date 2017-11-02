<?php

namespace Rougin\Staticka;

use zz\Html\HTMLMinify;

/**
 * Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Minifier
{
    /**
     * Minifies the given HTML.
     *
     * @param  string $html
     * @param  array  $options
     * @return string
     */
    public function minify($html, $options = array())
    {
        $level = HTMLMinify::OPTIMIZATION_ADVANCED;

        $options['optimizationLevel'] = $level;

        return HTMLMinify::minify($html, $options);
    }
}
