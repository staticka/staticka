<?php

namespace Staticka\Helper;

use Staticka\Render;
use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PlateHelperTest extends Testcase
{
    /**
     * @var \Staticka\Helper\PlateHelper
     */
    protected $helper;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/Plates';

        $render = new Render($path);

        $this->helper = new PlateHelper($render);
    }

    /**
     * @return void
     */
    public function test_helper_name()
    {
        $actual = $this->helper->name();

        $expected = (string) 'plate';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_html_render()
    {
        $expected = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
</body>
</html>';

        $actual = $this->helper->add('simple');

        $actual = str_replace("\r", '', $actual);

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $actual);
    }
}
