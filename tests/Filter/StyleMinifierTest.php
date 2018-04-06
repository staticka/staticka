<?php

namespace Staticka\Filter;

/**
 * Style Minifier Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StyleMinifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var \Staticka\Filter\FilterInterface
     */
    protected $filter;

    /**
     * Sets up the filter instance.
     */
    public function setUp()
    {
        $name = (string) str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new StyleMinifier;

        $this->code = file_get_contents($name . '/Style.css');
    }

    /**
     * Tests FilterInterface::filter.
     *
     * @return void
     */
    public function testFilterMethod()
    {
        $expected = 'body{margin:0px;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',\'Roboto\',\'Oxygen\',\'Ubuntu\',\'Cantarell\',\'Fira Sans\',\'Droid Sans\',\'Helvetica Neue\',sans-serif;line-height:1.5em;}@media screen and (min-width:576px){body{font-size:1.5em;}}p,small,.form-control{font-family:Georgia,serif;}p,.form-control{font-size:inherit;}.btn{font-size:inherit;font-family:inherit;}.content{margin:1em auto;max-width:40em;padding:0 .62em;line-height:1.5;}@media print{body{max-width:none}}a{color:#23241f;}a:hover{color:#666;}';

        $result = $this->filter->filter($this->code);

        $this->assertEquals($expected, $result);
    }
}
