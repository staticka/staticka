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
    public function test_passed_if_helper_name_returned()
    {
        $actual = $this->helper->name();

        $expect = 'plate';

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_html_rendered()
    {
        $expect = '<!DOCTYPE html>
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

        $expect = str_replace("\r", '', $expect);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/Plates';

        $render = new Render($path);

        $this->helper = new PlateHelper($render);
    }
}
