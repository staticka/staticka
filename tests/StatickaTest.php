<?php

namespace Staticka;

use Staticka\Content\MarkdownContent;
use Staticka\Filter\HtmlMinifier;
use Staticka\Helper\LinkHelper;
use Zapheus\Renderer\Renderer;

/**
 * Staticka Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StatickaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Staticka\Staticka
     */
    protected $app;

    /**
     * @var string
     */
    protected $output;

    /**
     * Sets up the Staticka instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->output = __DIR__ . DIRECTORY_SEPARATOR . 'build';

        $app = new Staticka(null, null);

        $app->page('/', function () {
            $text = '# Hello World';

            return $text . PHP_EOL;
        });

        $this->app = $app;
    }

    /**
     * Tests Staticka::compile.
     *
     * @return void
     */
    public function testCompileMethod()
    {
        $this->app->compile($this->output);

        $expected = '<h1>Hello World</h1>';

        $output = $this->output . '/index.html';

        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Staticka::compile with a file.
     *
     * @return void
     */
    public function testCompileMethodWithFile()
    {
        $file = __DIR__ . '/Fixture/HelloWorld.md';

        $content = new MarkdownContent;

        $this->app->page('hello', $file);

        $this->app->compile($this->output);

        $contents = file_get_contents($file);

        $expected = $content->make($contents);

        $output = $this->output . '/hello/index.html';

        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Staticka::compile with a filter.
     *
     * @return void
     */
    public function testCompileMethodWithFilter()
    {
        $this->app->filter(new HtmlMinifier);

        $this->app->compile($this->output);

        $expected = '<h1>Hello World</h1>';

        $output = $this->output . '/index.html';

        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Staticka::compile with a renderer.
     *
     * @return void
     */
    public function testCompileMethodWithRenderer()
    {
        $content = new MarkdownContent;

        $file = __DIR__ . '/Fixture/HelloWorld.md';

        $renderer = new Renderer(__DIR__ . '/Fixture');

        $app = new Staticka(null, $renderer);

        $app->helper($url = new LinkHelper('https://rougin.github.io'));

        $app->page('template', $file, 'defaultlayout');

        $app->compile($this->output);

        $content = $content->make(file_get_contents($file));

        $data = array('content' => $content, 'url' => $url);

        $expected = $renderer->render('defaultlayout', $data);

        $output = $this->output . '/template/index.html';

        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Staticka::content.
     *
     * @return void
     */
    public function testContentMethod()
    {
        $expected = 'Staticka\Content\ContentInterface';

        $result = $this->app->content();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests Staticka::renderer.
     *
     * @return void
     */
    public function testRendererMethod()
    {
        $expected = 'Zapheus\Renderer\RendererInterface';

        $result = $this->app->renderer();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests Staticka::transfer.
     *
     * @return void
     */
    public function testTransferMethod()
    {
        $this->app->compile($this->output);

        $this->app->transfer(__DIR__ . '/Sample');

        $file = __DIR__ . '/build/Styles.css';

        $this->assertFileExists($file);
    }
}
