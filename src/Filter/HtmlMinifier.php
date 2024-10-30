<?php

namespace Staticka\Filter;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HtmlMinifier implements FilterInterface
{
    /**
     * @var string[]
     */
    protected $data = array();

    /**
     * Filters the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    public function filter($code)
    {
        if (strpos($code, '<html') === false)
        {
            return $this->minify($code);
        }

        $utf8 = '<?xml encoding="UTF-8">';

        $dom = new \DOMDocument;

        @$dom->loadHTML($utf8 . $code);

        /** @var \DOMElement[] */
        $elements = $dom->getElementsByTagName('*');

        $this->remove($elements);

        /** @var string */
        $html = $dom->saveHTML();

        $html = $this->minify($html);

        return $this->restore($html);
    }

    /**
     * Checks if a specified node has children.
     *
     * @param \DOMNode $node
     *
     * @return boolean
     */
    protected function childbearing(\DOMNode $node)
    {
        if ($node->hasChildNodes())
        {
            /** @var \DOMElement $child */
            foreach ($node->childNodes as $child)
            {
                if ($child->nodeType === XML_ELEMENT_NODE)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Minifies the specified HTML.
     *
     * @param string $html
     *
     * @return string
     */
    protected function minify($html)
    {
        $encoding = '<?xml encoding="UTF-8">';

        $html = str_replace($encoding, '', $html);

        /** @var string */
        $html = preg_replace('/\s+/', ' ', $html);

        /** @var string */
        $html = str_replace('> <', '><', trim($html));

        $search = array(' />', '/>');

        return str_replace($search, '>', $html);
    }

    /**
     * Removes the content of single elements.
     *
     * @param \DOMElement[] $elements
     *
     * @return void
     */
    protected function remove($elements)
    {
        $encoded = array('textarea', 'code');

        foreach ($elements as $element)
        {
            if ($this->childbearing($element))
            {
                continue;
            }

            $output = $element->nodeValue;

            if (in_array($element->nodeName, $encoded))
            {
                /** @var string */
                $value = $element->nodeValue;

                $output = htmlentities($value);
            }

            array_push($this->data, $output);

            $current = count($this->data) - 1;

            $element->nodeValue = '$' . $current . '$';
        }
    }

    /**
     * Restores the data into the minified HTML.
     *
     * @param string $html
     *
     * @return string
     */
    protected function restore($html)
    {
        foreach ($this->data as $index => $item)
        {
            $key = '$' . $index . '$';

            $html = str_replace($key, $item, $html);
        }

        return $html;
    }
}
