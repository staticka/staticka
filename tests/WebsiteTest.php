<?php

namespace Staticka;

use Staticka\Content\MarkdownContent;
use Staticka\Factories\PageFactory;
use Staticka\Filter\HtmlMinifier;
use Staticka\Helper\LinkHelper;

/**
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
        $output = (string) __DIR__ . '/Output';

        $this->output = (string) $output;

        $this->site = new Website;

        if (! file_exists($output . '/.git'))
        {
            mkdir($output . '/.git', 0700, true);
        }

        $this->site->page('# Hello World');
    }

    /**
     * @return void
     */
    public function test_compiling_pages()
    {
        $this->site->compile($this->output);

        $expected = '<h1>Hello World</h1>';

        $output = $this->output . '/index.html';

        /** @var string */
        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_compiling_pages_with_file()
    {
        $content = new MarkdownContent;

        $type = $content->extension();
        $file = __DIR__ . '/Fixture/World.' . $type;

        $this->site->page($file);

        $this->site->compile($this->output);

        /** @var string */
        $contents = file_get_contents($file);

        $expected = $content->make($contents);

        $output = $this->output . '/index.html';

        /** @var string */
        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_compiling_pages_with_filter()
    {
        $this->site->filter(new HtmlMinifier);

        $this->site->compile($this->output);

        $expected = '<h1>Hello World</h1>';

        $output = $this->output . '/index.html';

        /** @var string */
        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_compiling_pages_with_renderer()
    {
        $content = new MarkdownContent;

        $file = __DIR__ . '/Fixture/World.' . $content->extension();

        $renderer = new Renderer(__DIR__ . '/Fixture');

        $site = new Website($renderer, $content);

        $site->helper($url = new LinkHelper('https://roug.in'));

        $data = array('layout' => 'layout');
        $data['permalink'] = 'template';

        $site->page($file, (array) $data);

        $site->compile($this->output);

        /** @var string */
        $file = file_get_contents($file);

        $content = $content->make($file);

        $data = array('content' => $content);
        $data['url'] = $url;
        $data['title'] = 'Hello World';

        $expected = $renderer->render('layout', $data);

        /** @var string */
        $result = file_get_contents($this->output . '/template/index.html');

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_content_interface()
    {
        $expected = 'Staticka\Content\ContentInterface';

        $result = $this->site->content();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * @return void
     */
    public function test_transferring_files()
    {
        $this->site->compile($this->output);

        $this->site->transfer(__DIR__ . '/Sample');

        $file = __DIR__ . '/Output/Styles/Site.css';

        $this->assertFileExists($file);
    }

    /**
     * @return void
     */
    public function test_adding_pages()
    {
        $page = new PageFactory(new Layout);

        $data = array('filters' => array());

        $filter = new HtmlMinifier;
        $data['filters'] = array($filter);

        $helper = new LinkHelper('https://roug.in');
        $data['helpers'] = array($helper);

        $file = __DIR__ . '/Fixture/World.md';
        $page = $page->file($file, $data);

        $this->site->add($page);

        $this->site->compile($this->output);

        $content = new MarkdownContent;

        /** @var string */
        $contents = file_get_contents($file);

        $expected = $content->make($contents);

        $expected = $data['filters'][0]->filter($expected);

        $output = $this->output . '/index.html';

        /** @var string */
        $result = file_get_contents($output);

        $this->assertEquals($expected, $result);
    }
}
