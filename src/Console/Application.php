<?php

namespace Rougin\Staticka\Console;

/**
 * Console Application
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * Initializes the console application instance.
     *
     * @param string $version
     */
    public function __construct($version = '0.1.0')
    {
        parent::__construct('Staticka', $version);

        $this->add(new Commands\BuildCommand);
        $this->add(new Commands\WatchCommand);
    }
}
