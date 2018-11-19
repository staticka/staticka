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
        $expected = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Document</title><style> body { background: #fff; } </style></head><body><h1>Brave World</h1><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto fugit voluptate obcaecati quidem tenetur consequatur incidunt optio sit est illum accusantium laudantium necessitatibus, saepe nobis enim tempore magnam, eius mollitia.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat animi et, vitae minus. Fugit alias dolorum minima facilis tempore, quod minus, voluptas autem, harum illo aperiam consequuntur amet? Rerum, veritatis.</p><div><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae sapiente esse quae est natus beatae sint eius unde ex, repellendus quas sunt, ratione excepturi minus reprehenderit error deleniti non. Vitae!</p></div><textarea name="content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore deleniti incidunt ea id quae ipsam dolores temporibus aspernatur nisi provident distinctio natus odit, magnam architecto recusandae earum, est unde iure.

Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium, maxime soluta sapiente quos sunt inventore! Fugiat nisi itaque dolorem aperiam, cum rerum officiis, laboriosam! Quidem illum quam nam architecto ullam.</textarea></body></html>';

        $result = $this->filter->filter($this->code);

        $this->assertEquals($expected, $result);
    }
}
