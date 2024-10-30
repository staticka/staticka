<?php

namespace Staticka;

use Staticka\Filter\LayoutFilter;
use Staticka\Helper\BlockHelper;
use Staticka\Helper\LayoutHelper;
use Staticka\Helper\PagesHelper;
use Staticka\Helper\PlateHelper;
use Staticka\Helper\StringHelper;
use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LayoutTest extends Testcase
{
    /**
     * @return void
     */
    public function test_sample_page()
    {
        $expected = $this->getHtml('CustomLayout');

        $render = new Render(__DIR__ . '/Fixture/Plates');

        $layout = new Layout;

        $page = new Page(__DIR__ . '/Fixture/Pages/CustomFilter.md');

        $layout->addFilter(new LayoutFilter);
        $layout->addHelper(new BlockHelper);
        $layout->addHelper(new LayoutHelper($render));
        $layout->addHelper(new PlateHelper($render));
        $layout->addHelper(new StringHelper);
        $layout->addHelper(new PagesHelper(array($page)));
        $layout->setName('Custom.php');

        $page->setLayout($layout);

        $actual = $this->getActual($page, $render);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param \Staticka\Page                   $page
     * @param \Staticka\Render\RenderInterface $render
     *
     * @return string
     */
    protected function getActual(Page $page, RenderInterface $render)
    {
        $parser = new Parser($render);

        $actual = $parser->parsePage($page);

        $result = $actual->getHtml();

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
}
