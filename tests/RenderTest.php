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
    public function test_failed_if_render_error()
    {
        $this->doExpectException('InvalidArgumentException');

        $this->renderer->render('hello');
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->renderer = new Render(__DIR__ . '/Fixture');
    }
}
