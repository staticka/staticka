<?php

namespace Staticka;

use Staticka\Filter\HtmlMinifier;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageTest extends Testcase
{
    /**
     * @var \Staticka\Parser
     */
    protected $parser;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $this->parser = new Parser;
    }

    /**
     * @return void
     */
    public function test_passed_if_from_constructor()
    {
        $expect = $this->getHtml('SimplePlate');

        $page = new Page("# {NAME}\nThis is a sample page.", Page::TYPE_BODY);

        $page->setName('Hello world!');

        $actual = $this->getActual($page);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_from_markdown_file()
    {
        $expect = $this->getHtml('FromMdFile');

        $file = __DIR__ . '/Fixture/Pages/HelloWorld.md';

        $page = new Page($file);

        $actual = $this->getActual($page);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_from_string()
    {
        $expect = $this->getHtml('SimplePlate');

        $page = new Page;

        $page->setName('Hello world!');

        $page->setBody("# {NAME}\nThis is a sample page.");

        $actual = $this->getActual($page);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_with_front_matter()
    {
        $expect = $this->getHtml('FrontMatter');

        $file = __DIR__ . '/Fixture/Pages/FrontMatter.md';

        $page = new Page($file);

        $actual = $this->getActual($page);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_with_minifier()
    {
        $expect = $this->getHtml('WithMinifier');

        $file = __DIR__ . '/Fixture/Pages/WithMinifier.md';

        $this->parser->addFilter(new HtmlMinifier);

        $page = new Page($file);

        $actual = $this->getActual($page);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return string
     */
    protected function getActual(Page $page)
    {
        $actual = $this->parser->parsePage($page);

        return $actual->getHtml();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getHtml($name)
    {
        $file = __DIR__ . '/Fixture/Output/' . $name . '.html';

        /** @var string */
        $actual = file_get_contents($file);

        return str_replace("\r\n", "\n", $actual);
    }
}
