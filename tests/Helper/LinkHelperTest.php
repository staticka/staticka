<?php

namespace Staticka\Helper;

/**
 * Link Helper Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class LinkHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Staticka\Helper\HelperInterface
     */
    protected $helper;

    /**
     * Sets up the helper instance.
     */
    public function setUp()
    {
        $link = 'https://rougin.github.io';

        $this->helper = new LinkHelper($link);
    }

    /**
     * Tests LinkHelper::set.
     *
     * @return void
     */
    public function testSetMethod()
    {
        $expected = 'https://rougin.github.io/slytherin';

        $result = $this->helper->set('slytherin');

        $this->assertEquals($expected, $result);
    }
}
