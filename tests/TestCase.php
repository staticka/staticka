<?php

namespace Rougin\Staticka;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $application;

    public function setUp()
    {
        $this->application = new Console\Application;
    }
}