<?php

namespace Staticka\Filter;

/**
 * HTML Minifier
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HtmlMinifier implements FilterInterface
{
    /**
     * Filters the specified code.
     *
     * @param  string $code
     * @return string
     */
    public function filter($code)
    {
        $flag = PREG_SPLIT_DELIM_CAPTURE;

        $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');

        list($buffer, $replace) = array('', array('>', '<', '\\1'));

        $blocks = preg_split('/(<\/?pre[^>]*>)/', $code, null, $flag);

        foreach ((array) $blocks as $i => $block) {
            $replaced = preg_replace($search, $replace, $block);

            $buffer .= $i % 4 === 2 ? (string) $block : $replaced;
        }

        $search = array(' />', '> <');

        return str_replace($search, array('/>', '><'), $buffer);
    }
}
