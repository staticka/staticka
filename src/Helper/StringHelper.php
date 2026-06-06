<?php

namespace Staticka\Helper;

use Parsedown;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StringHelper implements HelperInterface
{
    /**
     * @var \Parsedown
     */
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parsedown;
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'str';
    }

    /**
     * @param string  $text
     * @param integer $length
     *
     * @return string
     */
    public function truncate($text, $length = 80)
    {
        if (strlen($text) >= $length)
        {
            /** @var string */
            $parsed = $this->parser->parse($text);

            $html = strip_tags($parsed);

            $text = substr($html, 0, $length) . '...';
        }

        return $text;
    }
}
