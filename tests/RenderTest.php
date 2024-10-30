<?php

namespace Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RenderTest extends Testcase
{
    /**
     * @var \Staticka\Render\RenderInterface
     */
    protected $renderer;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->renderer = new Render(__DIR__ . '/Fixture');
    }

    /**
     * @return void
     */
    public function test_render_with_error()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->renderer->render('hello');
    }
}
