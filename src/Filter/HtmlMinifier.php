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
        if (strpos($code, '<html') === false)
        {
            return $this->minify($code);
        }

        list($uniqid, $code) = $this->parse($code);

        $excluded = (array) $this->excluded($code);

        $html = $this->minify($code, (string) $uniqid);

        foreach ((array) $excluded as $item)
        {
            $original = str_replace($uniqid, "\n", $item);

            $current = $this->minify(htmlspecialchars($item));

            if ($current && strpos($html, $current) === false)
            {
                $encoded = htmlspecialchars($item, ENT_QUOTES);

                $current = $this->minify((string) $encoded);
            }

            $html = str_replace($current, $original, $html);
        }

        $html = str_replace($uniqid, '', trim($html));

        return str_replace('> <', '><', (string) $html);
    }

    /**
     * Returns elements to be excluded when minifying.
     *
     * @param  string $html
     * @return array
     */
    protected function excluded($html)
    {
        libxml_use_internal_errors(true);

        $data = array();

        $utf8 = (string) '<?xml encoding="UTF-8">';

        $dom = new \DOMDocument;

        $dom->loadHTML((string) $utf8 . $html);

        $allowed = array('code', 'textarea', 'pre');

        $items = $dom->getElementsByTagName('*');

        foreach ($items as $node)
        {
            if (in_array($node->nodeName, $allowed))
            {
                array_push($data, $node->nodeValue);
            }
        }

        return (array) $data;
    }

    /**
     * Minifies the specified HTML.
     *
     * @param  string      $html
     * @param  string|null $uniqid
     * @return string
     */
    protected function minify($html, $uniqid = null)
    {
        $html = str_replace('<?xml encoding="UTF-8">', '', $html);

        $html = preg_replace('/\s+/', ' ', $html);

        $html = str_replace('> <', '><', (string) $html);

        if ($uniqid)
        {
            $html = str_replace($uniqid . ' <', $uniqid . '<', $html);
        }

        return str_replace(array(' />', '/>'), '>', $html);
    }

    /**
     * Parses the spaces to the entire HTML.
     *
     * @param  string $html
     * @return string
     */
    protected function parse($html)
    {
        $search = (array) array("\r\n", "\n");

        $uniqid = (string) uniqid();

        $code = str_replace($search, $uniqid, $html);

        return array($uniqid, (string) $code);
    }
}
