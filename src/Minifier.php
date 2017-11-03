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
     * Minifies the given code.
     *
     * @param  string $code
     * @param  array  $options
     * @return string
     */
    public function minify($code, $options = array())
    {
        $level = HTMLMinify::OPTIMIZATION_ADVANCED;

        $options['optimizationLevel'] = $level;

        return HTMLMinify::minify($code, $options);
    }
}
