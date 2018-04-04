<?php

namespace Staticka;

use Staticka\Content\MarkdownContent;
use Staticka\Filter\HtmlMinifier;
use Staticka\Helper\LinkHelper;
use Zapheus\Renderer\Renderer;

/**
 * Website Test
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WebsiteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Staticka\Website
     */
    protected $site;

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
        $separator = (string) DIRECTORY_SEPARATOR;

        $this->output = __DIR__ . $separator . 'build';

        $this->site = new Website(null, null);

        $this->site->page((string) '# Hello World');
    }

    /**
     * Tests Staticka::compile.
     *
     * @return void
     */
    public function testCompileMethod()
    {
        $this->site->compile($this->output);

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
        $content = new MarkdownContent;

        $file = __DIR__ . '/Fixture/World.' . $content->extension();

        $this->site->page($file);

        $this->site->compile($this->output);

        $contents = file_get_contents($file);

        $expected = $content->make($contents);

        $output = $this->output . '/world/index.html';

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
        $this->site->filter(new HtmlMinifier);

        $this->site->compile($this->output);

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

        $file = __DIR__ . '/Fixture/World.' . $content->extension();

        $renderer = new Renderer(__DIR__ . '/Fixture');

        $site = new Website($renderer);

        $site->helper($url = new LinkHelper('https://rougin.github.io'));

        $site->page($file, array('layout' => 'layout', 'permalink' => 'template'));

        $site->compile($this->output);

        $content = $content->make(file_get_contents($file));

        $data = array('content' => $content, 'url' => $url, 'title' => 'Hello World');

        $expected = $renderer->render('layout', $data);

        $result = file_get_contents($this->output . '/template/index.html');

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

        $result = $this->site->content();

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

        $result = $this->site->renderer();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests Staticka::transfer.
     *
     * @return void
     */
    public function testTransferMethod()
    {
        $this->site->compile($this->output);

        $this->site->transfer(__DIR__ . '/Sample');

        $file = __DIR__ . '/build/Styles.css';

        $this->assertFileExists($file);
    }
}
