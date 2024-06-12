<?php

namespace Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RendererTest extends Testcase
{
    /**
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->renderer = new Renderer(__DIR__ . '/Fixture');
    }

    /**
     * @return void
     */
    public function test_rendering_a_text_from_file_with_an_error()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->renderer->render('hello');
    }
}
