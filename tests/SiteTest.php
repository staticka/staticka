<?php

namespace Staticka;

use Staticka\Filter\HtmlMinifier;
use Staticka\Filter\ScriptMinifier;
use Staticka\Filter\StyleMinifier;
use Staticka\Helper\LinkHelper;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SiteTest extends Testcase
{
    /**
     * @var \Staticka\Site
     */
    protected $site;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $this->site = new Site;
    }

    /**
     * @return void
     */
    public function test_build_with_pages()
    {
        $expected = $this->getHtml('FromMdFile');

        $file = __DIR__ . '/Fixture/Pages/HelloWorld.md';

        $page = new Page($file);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('index');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_build_with_pages_front_matter()
    {
        $expected = $this->getHtml('FrontMatter');

        $file = __DIR__ . '/Fixture/Pages/FrontMatter.md';

        $page = new Page($file);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('hello-world');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_build_with_pages_front_matter
     *
     * @return void
     */
    public function test_copy_entire_build_path()
    {
        $path = __DIR__ . '/Fixture/Build';

        $dest = __DIR__ . '/Fixture/Copied';

        $this->site->copyDir($path, $dest);

        $exists = $dest . '/hello-world/index.html';

        $this->assertTrue(file_exists($exists));
    }

    /**
     * @return void
     */
    public function test_with_data()
    {
        $expected = $this->getHtml('WithSiteData');

        $file = __DIR__ . '/Fixture/Pages/WithSiteData.md';

        $page = new Page($file);

        $data = array('website' => 'https://roug.in');

        $this->site->setData($data);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('index');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_with_filters()
    {
        $expected = $this->getHtml('WithFilter');

        $file = __DIR__ . '/Fixture/Pages/WithFilter.md';

        $this->withRender();

        $page = new Page($file);
        $page->setLayout($this->getLayout());
        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('home');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_with_layout()
    {
        $expected = $this->getHtml('WithLayout');

        $file = __DIR__ . '/Fixture/Pages/WithLayout.md';

        $this->withRender();

        $this->site->addPage(new Page($file));

        $this->buildSite();

        $actual = $this->getActualHtml('world');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_with_plate()
    {
        $expected = $this->getHtml('WithPlate');

        $file = __DIR__ . '/Fixture/Pages/WithPlate.md';

        $this->withRender();

        $this->site->addPage(new Page($file));

        $this->buildSite();

        $actual = $this->getActualHtml('hello');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    protected function buildSite()
    {
        $path = __DIR__ . '/Fixture/Build';

        $this->site->emptyDir($path)->build($path);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getActualHtml($name)
    {
        $name = $name !== 'index' ? $name . '/index' : 'index';

        $file = __DIR__ . '/Fixture/Build/' . $name . '.html';

        /** @var string */
        $result = file_get_contents($file);

        return str_replace("\r\n", "\n", $result);
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
        $result = file_get_contents($file);

        return str_replace("\r\n", "\n", $result);
    }

    /**
     * @return \Staticka\Layout
     */
    protected function getLayout()
    {
        $layout = new Layout;
        $layout->setName('filtered');

        $layout->addFilter(new StyleMinifier);
        $layout->addFilter(new ScriptMinifier);
        $layout->addFilter(new HtmlMinifier);

        $link = 'https://roug.in';
        $helper = new LinkHelper($link);
        $layout->addHelper($helper);

        return $layout;
    }

    /**
     * @return void
     */
    protected function withRender()
    {
        $path = __DIR__ . '/Fixture/Plates';

        $parser = new Parser;

        $parser->setRender(new Render($path));

        $this->site->setParser($parser);
    }
}
