<?php

namespace Staticka\Filter;

use Staticka\Helper\LayoutHelper;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LayoutFilter implements FilterInterface
{
    /**
     * @param string $code
     *
     * @return string
     */
    public function filter($code)
    {
        // Return the layout element name ---
        $prefix = LayoutHelper::PREFIX;

        $layout = $this->getLayoutBlock();
        // ----------------------------------

        // Find all elements with the prefix -------------------
        preg_match_all("<$prefix-([a-z])\w+>", $code, $matches);

        $items = array_unique(array_unique($matches[0]));
        // -----------------------------------------------------

        $parsed = array();

        foreach ($items as $item)
        {
            $result = $this->findBlock($code, $item);

            // Put to temporary array if results found ---
            $row = array('code' => '', 'data' => '');
            // -------------------------------------------

            // @codeCoverageIgnoreStart
            if (! isset($result[0][0]))
            {
                continue;
            }
            // @codeCoverageIgnoreEnd

            // e.g., <x-layout></x-layout> ---
            $row['code'] = $result[0][0];
            // -------------------------------

            // e.g., <x-layout>Hello world</x-layout> ---
            $row['orig'] = '';
            // ------------------------------------------

            // e.g., Hello world ----------------
            if ($item === $layout)
            {
                $row['data'] = $result[1][0];
            }
            else
            {
                if (isset($result[1][1]))
                {
                    $row['orig'] = $result[0][1];

                    $row['data'] = $result[1][1];
                }
            }
            // ----------------------------------

            $parsed[] = $row;
        }

        $html = $code;

        foreach ($parsed as $item)
        {
            // Replace the empty element with its data ---
            $code = $item['code'];

            $data = $item['data'];

            $html = str_replace($code, $data, $html);
            // -------------------------------------------

            // Remove the element with its tag -------
            if ($orig = $item['orig'])
            {
                $html = str_replace($orig, '', $html);
            }
            // ---------------------------------------
        }

        return $html;
    }

    /**
     * @link https://stackoverflow.com/a/1445528
     *
     * @param string $code
     * @param string $name
     *
     * @return string[][]
     */
    protected function findBlock($code, $name)
    {
        $startTag = '#' . preg_quote('<' . $name . '>', '#');

        $endTag = preg_quote('</' . $name . '>', '#') . '#';

        preg_match_all("{$startTag}(.*?){$endTag}s", $code, $matches);

        return (array) $matches;
    }

    /**
     * @return string
     */
    protected function getLayoutBlock()
    {
        return LayoutHelper::PREFIX . '-' . LayoutHelper::LAYOUT;
    }
}
