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
     * @var \Staticka\Contracts\FilterContract
     */
    protected $filter;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $name = (string) str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new ScriptMinifier;

        /** @var string */
        $code = file_get_contents($name . '/Script.html');

        $this->code = $code;
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

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $result);
    }
}
