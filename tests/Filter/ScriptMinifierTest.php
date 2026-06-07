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
    public function test_passed_if_script_minified()
    {
        $expect = '<!DOCTYPE html>
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

        $expect = str_replace("\r", '', $expect);

        $this->assertEquals($expect, $actual);
    }

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
}
