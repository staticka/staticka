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
     * Tests HelperInterface::name.
     *
     * @return void
     */
    public function testNameMethod()
    {
        $result = $this->helper->name();

        $expected = (string) 'view';

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ViewHelper::set.
     *
     * @return void
     */
    public function testSetMethod()
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
