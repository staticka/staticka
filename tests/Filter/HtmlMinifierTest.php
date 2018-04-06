<?php

namespace Staticka\Filter;

/**
 * HTML Minifier Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HtmlMinifierTest extends \PHPUnit_Framework_TestCase
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

        $this->filter = new HtmlMinifier;

        $this->code = file_get_contents($name . '/Sample.html');
    }

    /**
     * Tests FilterInterface::filter.
     *
     * @return void
     */
    public function testFilterMethod()
    {
        $expected = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Document</title><style> body { background: #fff; } </style></head><body><h1>Brave World</h1><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto fugit voluptate obcaecati quidem tenetur consequatur incidunt optio sit est illum accusantium laudantium necessitatibus, saepe nobis enim tempore magnam, eius mollitia.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat animi et, vitae minus. Fugit alias dolorum minima facilis tempore, quod minus, voluptas autem, harum illo aperiam consequuntur amet? Rerum, veritatis.</p></body></html>';

        $result = $this->filter->filter($this->code);

        $this->assertEquals($expected, $result);
    }
}
