<?php

namespace Rougin\Staticka;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $this->application = new Console\Application;
    }
}