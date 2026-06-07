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
    public function test_passed_if_custom_fields_set()
    {
        $expect = array('name', 'link');

        $depot = $this->getDepot($expect);

        $actual = $depot->getFields();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_new_page_created()
    {
        $page = $this->getPage('NewDepot');

        $expect = $page->getLink();

        $data = array('name' => 'Hello!');

        $data['link'] = '/hello';

        $depot = $this->getDepot();

        $page = $depot->create($data);

        $page = $this->parser->parsePage($page);

        $actual = $page->getLink();

        $this->assertEquals($expect, $actual);

        $id = $page->getId();

        if ($id === null)
        {
            throw new \Exception('Page ID not found');
        }

        $depot->delete($id);
    }

    /**
     * @return void
     */
    public function test_passed_if_page_deleted()
    {
        $data = array('name' => 'Delete me!');

        $data['link'] = '/deleteme';

        $depot = $this->getDepot();

        $page = $depot->create($data);

        $page = $this->parser->parsePage($page);

        $id = $page->getId();

        if ($id === null)
        {
            throw new \Exception('Page ID not found');
        }

        $this->assertTrue($depot->delete($id));
    }

    /**
     * @return void
     */
    public function test_passed_if_page_found_by_id()
    {
        $page = $this->getPage('FromDepot');

        $expect = $page->getLink();

        $depot = $this->getDepot();

        $page = $depot->find(1704067200);

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_page_found_by_link()
    {
        $page = $this->getPage('FromDepot');

        $expect = $page->getLink();

        $depot = $this->getDepot();

        $page = $depot->findByLink('hello-world');

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_page_found_by_name()
    {
        $page = $this->getPage('FromDepot');

        $expect = $page->getLink();

        $depot = $this->getDepot();

        $page = $depot->findByName('Hello world!');

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getLink();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_page_updated()
    {
        $data = array('name' => 'Hello!');

        $data['link'] = '/update-me';

        $depot = $this->getDepot();

        $page = $depot->create($data);

        $page = $this->parser->parsePage($page);

        $id = $page->getId();

        if ($id === null)
        {
            throw new \Exception('Page ID not found');
        }

        $expect = 'This is updated! # And this is the body.';

        $data = array('name' => 'This is updated!');

        $data['body'] = '# And this is the body.';

        $page = $depot->update($id, $data);

        if (! $page)
        {
            throw new \Exception('Page not found');
        }

        $actual = $page->getName() . ' ' . $page->getBody();

        $this->assertEquals($expect, $actual);

        $depot->delete($id);
    }

    /**
     * @return void
     */
    public function test_passed_if_pages_from_array_data()
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

        $expect = array($row);

        $sort = PageDepot::SORT_DESC;

        $depot = $this->getDepot();

        $actual = $depot->getAsData($sort);

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/Fixture/Depot';

        $package = new Package($path);

        $this->package = $package->setPathsFromRoot();

        $this->parser = new Parser;
    }

    /**
     * @return void
     */
    protected function doTearDown()
    {
        $path = __DIR__ . '/Fixture/Depot/pages';

        $files = glob($path . '/*.md');

        if (! is_array($files))
        {
            return;
        }

        foreach ($files as $file)
        {
            if (basename($file) === '20240101000000_hello.md')
            {
                continue;
            }

            unlink($file);
        }
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

        $actual = $this->package->define($container, $config);

        $name = 'Staticka\System';

        /** @var \Staticka\System */
        return $actual->get($name);
    }
}
