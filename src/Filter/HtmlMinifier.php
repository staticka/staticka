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
        $xml = '<?xml encoding="UTF-8">';

        $dom = new \DOMDocument;

        $dom->loadHTML((string) $xml . $code);

        $items = $dom->getElementsByTagName('*');

        $elements = array();

        foreach ($items as $index => $node)
        {
            if ($node->nodeName === 'textarea')
            {
                $elements[$index] = $node->nodeValue;

                $node->nodeValue = '$' . $index . '$';
            }
        }

        $html = $this->minify($dom->saveHTML());

        foreach ($elements as $index => $item)
        {
            $html = str_replace('$' . $index . '$', $item, $html);
        }

        file_put_contents('test.html', trim($html));

        return trim($html);
    }

    protected function minify($html)
    {
        $html = str_replace('<?xml encoding="UTF-8">', '', $html);

        $html = preg_replace('/\s+/', ' ', $html);

        $html = str_replace('> <', '><', $html);

        return str_replace(array(' />', '/>'), '>', $html);
    }
}
