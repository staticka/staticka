<?php

namespace Rougin\Staticka;

/**
 * Test Case
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Staticka\Console\Application
     */
    protected $application;

    /**
     * Sets up the console application.
     *
     * @return void
     */
    public function setUp()
    {
        $this->application = new Console\Application;
    }
}
