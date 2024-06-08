<?php

namespace Staticka\Filter;

use Staticka\Testcase;

/**
 * Script Minifier Test
 *
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
     * Sets up the filter instance.
     */
    protected function doSetUp()
    {
        $name = (string) str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new ScriptMinifier;

        $this->code = file_get_contents($name . '/Script.html');
    }

    /**
     * Tests FilterInterface::filter.
     *
     * @return void
     */
    public function testFilterMethod()
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

        $result = $this->filter->filter($this->code);

        $result = str_replace("\r", '', $result);

        $this->assertEquals($expected, $result);
    }
}
