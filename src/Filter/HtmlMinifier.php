<?php

namespace Staticka\Filter;

/**
 * HTML Minifier
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class HtmlMinifier implements FilterInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * Filters the specified code.
     *
     * @param  string $code
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

        $dom->loadHTML((string) $utf8 . $code);

        $elements = $dom->getElementsByTagName('*');

        $this->remove($elements);

        $html = $this->minify($dom->saveHTML());

        return $this->restore((string) $html);
    }

    /**
     * Minifies the specified HTML.
     *
     * @param  string $html
     * @return string
     */
    protected function minify($html, $uniqid = null)
    {
        $html = str_replace('<?xml encoding="UTF-8">', '', $html);

        $html = trim(preg_replace('/\s+/', ' ', $html));

        $html = str_replace('> <', '><', $html);

        return str_replace(array(' />', '/>'), '>', $html);
    }

    /**
     * Removes the content of single elements.
     *
     * @param  \DOMNodeList $elements
     * @return void
     */
    protected function remove(\DOMNodeList $elements)
    {
        foreach ($elements as $element)
        {
            $length = $element->childNodes->length;

            if ($length > 1)
            {
                continue;
            }

            array_push($this->data, $element->nodeValue);

            $current = count($this->data) - 1;

            $element->nodeValue = '$' . $current . '$';
        }
    }

    /**
     * Restores the data into the minified HTML.
     *
     * @param  string $html
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
