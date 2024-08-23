<?php

namespace Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MatterTest extends Testcase
{
    /**
     * @return void
     */
    public function test_data_parsing()
    {
        $filename = __DIR__ . '/Fixture/Matter.md';

        /** @var string */
        $file = file_get_contents($filename);

        $result = Matter::parse($file);

        /** @var array<string, mixed> */
        $result = $result[0];

        $expected = array('title' => 'Brave and Bold');

        $this->assertEquals($expected, $result);
    }
}
