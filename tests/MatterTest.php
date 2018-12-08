<?php

namespace Staticka;

/**
 * Matter Test
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests Matter::parse.
     *
     * @return void
     */
    public function testParseMethod()
    {
        $filename = __DIR__ . '/Fixture/Matter.md';

        $file = file_get_contents((string) $filename);

        list($result, $content) = Matter::parse($file);

        $expected = array('title' => 'Brave and Bold');

        $this->assertEquals($expected, $result);
    }
}
