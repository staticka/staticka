<?php

namespace Staticka\Filters;

use Staticka\Contracts\FilterContract;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HtmlMinifier implements FilterContract
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

        $utf8 = (string) '<?xml encoding="UTF-8">';

        $dom = new \DOMDocument;

        @$dom->loadHTML((string) $utf8 . $code);

        $elements = $dom->getElementsByTagName('*');

        $this->remove($elements);

        $html = $this->minify($dom->saveHTML());

        return $this->restore((string) $html);
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
        $html = str_replace('<?xml encoding="UTF-8">', '', $html);

        $html = trim(preg_replace('/\s+/', ' ', $html));

        $html = str_replace('> <', '><', $html);

        $html = str_replace('&#039;', '\'', $html);

        return str_replace(array(' />', '/>'), '>', $html);
    }

    /**
     * Removes the content of single elements.
     *
     * @param \DOMNodeList $elements
     *
     * @return void
     */
    protected function remove(\DOMNodeList $elements)
    {
        $encoded = array('textarea', 'code');

        foreach ($elements as $element)
        {
            if ($this->childbearing($element))
            {
                continue;
            }

            $output = (string) $element->nodeValue;

            if (in_array($element->nodeName, $encoded))
            {
                $output = htmlentities($element->nodeValue);
            }

            array_push($this->data, (string) $output);

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
            $key = (string) '$' . $index . '$';

            $html = str_replace($key, $item, $html);
        }

        return (string) $html;
    }
}
