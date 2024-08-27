<?php

namespace Staticka\Filter;

use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StyleMinifierTest extends Testcase
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
        /** @var string */
        $name = str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new StyleMinifier;

        /** @var string */
        $code = file_get_contents($name . '/Style.html');

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
  <style>body{margin:0px;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',\'Roboto\',\'Oxygen\',\'Ubuntu\',\'Cantarell\',\'Fira Sans\',\'Droid Sans\',\'Helvetica Neue\',sans-serif;line-height:1.5em;}@media screen and (min-width:576px){body{font-size:1.5em;}}p,small,.form-control{font-family:Georgia,serif;}p,.form-control{font-size:inherit;}.btn{font-size:inherit;font-family:inherit;}.content{margin:1em auto;max-width:40em;padding:0 .62em;line-height:1.5;}@media print{body{max-width:none}}a{color:#23241f;}a:hover{color:#666;}</style>
</head>
<body>
</body>
</html>';

        $result = $this->filter->filter($this->code);

        $result = str_replace("\r", '', $result);

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $result);
    }
}
