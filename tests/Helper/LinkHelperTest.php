<?php

namespace Staticka\Helper;

use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LinkHelperTest extends Testcase
{
    /**
     * @var \Staticka\Helper\LinkHelper
     */
    protected $helper;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $link = 'https://roug.in';

        $this->helper = new LinkHelper($link);
    }

    /**
     * @return void
     */
    public function test_getting_link()
    {
        $expected = 'https://roug.in/staticka';

        $actual = $this->helper->set('staticka');

        $this->assertEquals($expected, $actual);
    }
}
