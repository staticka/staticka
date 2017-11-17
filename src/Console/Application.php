<?php

namespace Rougin\Staticka\Console;

use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Console Application
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Initializes the console application instance.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct('Staticka', $this->version);

        $this->add(new Commands\BuildCommand($container));
        $this->add(new Commands\WatchCommand);
    }
}
