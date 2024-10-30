<?php

namespace Staticka;

use Symfony\Component\Yaml\Yaml;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Matter
{
    /**
     * @param string $content
     *
     * @return array<integer, mixed>
     */
    public static function parse($content)
    {
        $matter = array();

        $text = str_replace(PHP_EOL, $id = uniqid(), $content);

        $regex = '/^---' . $id . '(.*?)' . $id . '---/';

        if (preg_match($regex, $text, $matches) === 1)
        {
            $yaml = str_replace($id, PHP_EOL, $matches[1]);

            /** @var array<string, mixed> */
            $matter = Yaml::parse(trim($yaml));

            $body = str_replace($matches[0], '', $text);

            $content = str_replace($id, PHP_EOL, $body);
        }

        return array($matter, trim($content));
    }
}
