<?php

namespace Rougin\Staticka;

/**
 * Console Application Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends TestCase
{
    /**
     * Checks if the version number from Console\Application instance
     * is the also same in the latest version of the CHANGELOG.md file.
     *
     * @return void
     */
    public function testSameVersionNumber()
    {
        $changelog = file(__DIR__ . '/../CHANGELOG.md');

        $expected = substr($changelog[4], 3, 5);

        $result = $this->application->getVersion();

        $this->assertEquals($expected, $result);
    }
}
