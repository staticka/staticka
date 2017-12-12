<?php

namespace Rougin\Staticka;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Build Command Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BuildCommandTest extends TestCase
{
    /**
     * Tests "build" command.
     *
     * @return void
     */
    public function testBuildCommand()
    {
        $command = new CommandTester($this->application->find('build'));

        $options = array('--source' => __DIR__ . '/Application');

        $options['--path'] = __DIR__ . '/Application/build';

        $command->execute($options);

        $output = $command->getDisplay();

        $this->assertContains('Site built successfully', $output);
    }
}
