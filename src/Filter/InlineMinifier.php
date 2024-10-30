<?php

namespace Staticka\Filter;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InlineMinifier implements FilterInterface
{
    /**
     * @var string
     */
    protected $tagname = '';

    /**
     * Initializes the filter instance.
     *
     * @param string $tagname
     */
    public function __construct($tagname = '')
    {
        $tagname !== '' && $this->tagname = $tagname;
    }

    /**
     * Filters the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    public function filter($code)
    {
        $elements = $this->elements($code);

        foreach ($elements as $element)
        {
            /** @var string */
            $original = $element->nodeValue;

            $minified = $this->minify($original);

            /** @var string */
            $minified = preg_replace('/\s+/', ' ', $minified);

            $minified = $this->minify($minified);

            $code = str_replace($original, $minified, $code);
        }

        return $code;
    }

    /**
     * Returns elements by a tag name.
     *
     * @param string $code
     *
     * @return \DOMElement[]
     */
    protected function elements($code)
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument;

        $doc->loadHTML($code);

        $tag = $this->tagname;

        $items = $doc->getElementsByTagName($tag);

        $items = iterator_to_array($items);

        return count($items) > 0 ? $items : array();
    }

    /**
     * Minifies the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    protected function minify($code)
    {
        $pattern = '!/\*[^*]*\*+([^/][^*]*\*+)*/!';

        /** @var string */
        $minified = preg_replace('/^\\s+/m', '', $code);

        /** @var string */
        $minified = preg_replace($pattern, '', $minified);

        /** @var string */
        $minified = preg_replace('/( )?}( )?/', '}', $minified);

        /** @var string */
        $minified = preg_replace('/( )?{( )?/', '{', $minified);

        /** @var string */
        $minified = preg_replace('/( )?:( )?/', ':', $minified);

        /** @var string */
        $minified = preg_replace('/( )?;( )?/', ';', $minified);

        /** @var string */
        $minified = preg_replace('/( )?,( )?/', ',', $minified);

        return $minified;
    }
}
