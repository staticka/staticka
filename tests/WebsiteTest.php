<?php

namespace Staticka;

use Staticka\Content\MarkdownContent;
use Staticka\Factories\PageFactory;
use Staticka\Filter\HtmlMinifier;
use Staticka\Helper\LinkHelper;

/**
 * Website Test
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WebsiteTest extends Testcase
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
    protected function doSetUp()
    {
        $separator = (string) DIRECTORY_SEPARATOR;

        $output = __DIR__ . $separator . 'Output';

        $this->output = (string) $output;

        $this->site = new Website;

        if (! file_exists($output . '/.git'))
        {
            mkdir($output . '/.git', 0700, true);
        }

        $this->site->page('# Hello World');
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

        $output = $this->output . '/index.html';

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

        $site = new Website($renderer, $content);

        $site->helper($url = new LinkHelper('https://roug.in'));

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
     * Tests Staticka::transfer.
     *
     * @return void
     */
    public function testTransferMethod()
    {
        $this->site->compile($this->output);

        $this->site->transfer(__DIR__ . '/Sample');

        $file = __DIR__ . '/Output/Styles/Site.css';

        $this->assertFileExists($file);
    }

    /**
     * Tests Staticka::compile.
     *
     * @return void
     */
    public function testAddMethod()
    {
        $page = new PageFactory(new Layout);

        $file = __DIR__ . '/Fixture/World.md';

        $data = array('filters' => array());
        $data['filters'] = array(new HtmlMinifier);
        $data['helpers'] = array(new LinkHelper('https://roug.in'));

        $page = $page->file($file, $data);

        $this->site->add($page);

        $this->site->compile($this->output);

        $content = new MarkdownContent;

        $contents = file_get_contents($file);

        $expected = $content->make($contents);

        $expected = $data['filters'][0]->filter($expected);

        $output = $this->output . '/index.html';

        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }
}
