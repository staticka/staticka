<?php

namespace Staticka;

use Symfony\Component\Yaml\Yaml;

/**
 * YAML Front Matter Parser
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Matter
{
    const PATTERN = '/^[\s\r\n]?---[\s\r\n]?$/sm';

    /**
     * Retrieves the contents from a YAML format.
     *
     * @param  string $content
     * @return array
     */
    public function parse($content)
    {
        $parts = preg_split(self::PATTERN, PHP_EOL . ltrim($content));

        $matter = count($parts) < 3 ? array() : Yaml::parse(trim($parts[1]));

        $body = implode(PHP_EOL . '---' . PHP_EOL, array_slice($parts, 2));

        return array((array) $matter, (string) $body ?: (string) $content);
    }
}
