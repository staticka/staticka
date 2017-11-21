<?php

namespace Rougin\Staticka;

use Symfony\Component\Console\Tester\CommandTester;

class BuildCommandTest extends TestCase
{
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
