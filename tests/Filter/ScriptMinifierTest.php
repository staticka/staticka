<?php

namespace Staticka\Filter;

use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ScriptMinifierTest extends Testcase
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
     * @return void
     */
    protected function doSetUp()
    {
        /** @var string */
        $name = str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new ScriptMinifier;

        /** @var string */
        $code = file_get_contents($name . '/Script.html');

        $this->code = $code;
    }

    /**
     * @return void
     */
    public function test_filter_from_html()
    {
        $expected = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <script>map=function(){variable=null;element=document.getElementById(\'rs-map\');place={lat:7.074744,lng:125.619585};map=new google.maps.Map(element,{zoom:17,center:place});marker=new google.maps.Marker({position:place,map:map});};</script>
</body>
</html>';

        $actual = $this->filter->filter($this->code);

        $actual = str_replace("\r", '', $actual);

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $actual);
    }
}
