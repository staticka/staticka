<?php

namespace Staticka;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Integration\Configuration;
use Staticka\Depots\PageDepot;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DepotTest extends Testcase
{
    /**
     * @var \Staticka\Package
     */
    protected $package;

    /**
     * @var \Staticka\Parser
     */
    protected $parser;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $path = __DIR__ . '/Fixture/Depot';

        $package = new Package($path);

        $this->package = $package->setPathsFromRoot();

        $this->parser = new Parser;
    }

    /**
     * @return void
     */
    public function test_array_data_pages()
    {
        $row = array('name' => 'Hello world!');
        $row['link'] = '/hello-world';
        $row['title'] = 'Hello world!';
        $row['id'] = 1704067200;
        $row['body'] = '# Hello world!';
        $row['html'] = '<h1>Hello world!</h1>';
        $row['created_at'] = 1704067200;
        $row['description'] = null;
        $row['tags'] = null;
        $row['category'] = null;

        $expected = array($row);

        $sort = PageDepot::SORT_DESC;

        $actual = $this->getDepot()->getAsData($sort);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return \Staticka\Page
     */
    public function test_create_new_page()
    {
        $page = $this->getPage('NewDepot');
        $expected = $page->getLink();

        $data = array();
        $data['name'] = 'Hello!';
        $data['link'] = '/hello';

        $depot = $this->getDepot();
        $page = $depot->create($data);
        $page = $this->parser->parsePage($page);
        $actual = $page->getLink();

        $this->assertEquals($expected, $actual);

        return $page;
    }

    /**
     * @return void
     */
    public function test_custom_fields()
    {
        $expected = array('name', 'link');

        $depot = $this->getDepot($expected);

        $actual = $depot->getFields();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_update_page
     *
     * @param \Staticka\Page $page
     *
     * @return void
     */
    public function test_delete_page(Page $page)
    {
        $depot = $this->getDepot();

        $id = (int) $page->getId();

        $this->assertTrue($depot->delete($id));
    }

    /**
     * @return void
     */
    public function test_find_page_by_id()
    {
        $page = $this->getPage('FromDepot');
        $expected = $page->getLink();

        $page = $this->getDepot()->find(1704067200);

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_find_page_by_link()
    {
        $page = $this->getPage('FromDepot');
        $expected = $page->getLink();

        $depot = $this->getDepot();
        $page = $depot->findByLink('hello-world');

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_find_page_by_name()
    {
        $page = $this->getPage('FromDepot');
        $expected = $page->getLink();

        $depot = $this->getDepot();
        $page = $depot->findByName('Hello world!');

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_create_new_page
     *
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    public function test_update_page(Page $page)
    {
        $expected = 'This is updated! # And this is the body.';

        $data = array('name' => 'This is updated!');

        $data['body'] = '# And this is the body.';

        $depot = $this->getDepot();

        $page = $depot->update((int) $page->getId(), $data);

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getName() . ' ' . $page->getBody();

        $this->assertEquals($expected, $actual);

        return $page;
    }

    /**
     * @param string[] $fields
     *
     * @return \Staticka\Depots\PageDepot
     */
    protected function getDepot($fields = array())
    {
        return new PageDepot($this->getSystem(), $fields);
    }

    /**
     * @param string $name
     *
     * @return \Staticka\Page
     */
    protected function getPage($name)
    {
        $file = __DIR__ . '/Fixture/Pages/' . $name . '.md';

        return $this->parser->parsePage(new Page($file));
    }

    /**
     * @return \Staticka\System
     */
    protected function getSystem()
    {
        $container = new Container;

        $config = new Configuration;

        $result = $this->package->define($container, $config);

        $name = 'Staticka\System';

        /** @var \Staticka\System */
        return $result->get($name);
    }

}
