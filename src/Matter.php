<?php

namespace Staticka;

use Symfony\Component\Yaml\Yaml;

/**
 * TODO: Remove this file after v1.0.0.
 * Move the code to PageFactory::parse instead.
 *
 * YAML Front Matter Parser
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Matter
{
    /**
     * Retrieves the contents from a YAML format.
     *
     * @param  string $content
     * @return array
     */
    public static function parse($content)
    {
        $matter = array();

        $text = str_replace(PHP_EOL, $id = uniqid(), $content);

        $regex = '/^---' . $id . '(.*?)' . $id . '---/';

        if (preg_match($regex, $text, $matches) === 1) {
            $yaml = str_replace($id, PHP_EOL, $matches[1]);

            $matter = (array) Yaml::parse(trim($yaml));

            $body = str_replace($matches[0], '', $text);

            $content = str_replace($id, PHP_EOL, $body);
        }

        return array($matter, (string) trim($content));
    }
}
