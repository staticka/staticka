<?php

namespace Staticka\Helper;

use Staticka\Website;
use Staticka\Renderer;
use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelperTest extends Testcase
{
    /**
     * @var \Staticka\Helper\ViewHelper
     */
    protected $helper;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $renderer = new Renderer(__DIR__ . '/../Fixture');

        $website = new Website($renderer);

        $this->helper = new ViewHelper($website);
    }

    /**
     * @return void
     */
    public function test_helper_name()
    {
        $result = $this->helper->name();

        $expected = (string) 'view';

        $this->assertEquals($expected, $result);
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

        $result = $this->helper->render('basic');

        $result = str_replace("\r", '', $result);

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $result);
    }
}
