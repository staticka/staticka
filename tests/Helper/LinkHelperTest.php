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
     * Tests LinkHelper::set.
     *
     * @return void
     */
    public function testSetMethod()
    {
        $expected = 'https://roug.in/slytherin';

        $result = $this->helper->set('slytherin');

        $this->assertEquals($expected, $result);
    }
}
