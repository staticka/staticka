<?php

namespace Rougin\Staticka\Console;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct($version = '0.1.0')
    {
        parent::__construct('Staticka', $version);

        $this->add(new Commands\BuildCommand);
    }
}
