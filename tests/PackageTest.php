<?php

namespace Staticka;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Integration\Configuration;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PackageTest extends Testcase
{
    /**
     * @var \Staticka\Package
     */
    protected $package;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $path = __DIR__ . '/Fixture/Sample';

        $package = new Package($path);

        $this->package = $package->setPathsFromRoot();
    }

    /**
     * @return void
     */
    public function test_passed_if_build_path_set()
    {
        $expect = __DIR__ . '/Fixture/Sample/build';

        $app = $this->getSystem();

        $actual = $app->getBuildPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_config_path_set()
    {
        $expect = __DIR__ . '/Fixture/Sample/config';

        $app = $this->getSystem();

        $actual = $app->getConfigPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_custom_parser_set()
    {
        $expect = new Parser;

        $app = $this->getSystem();

        $app->setParser($expect);

        $actual = $app->getParser();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_page_items_loaded()
    {
        $path = __DIR__ . '/Fixture/Pages';

        $expect = 10;

        $this->package->setPagesPath($path);

        $app = $this->getSystem();

        $actual = $app->getPages();

        $this->assertCount($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_pages_path_set()
    {
        $expect = __DIR__ . '/Fixture/Sample/pages';

        $app = $this->getSystem();

        $actual = $app->getPagesPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_plates_path_set()
    {
        $expect = __DIR__ . '/Fixture/Sample/plates';

        $app = $this->getSystem();

        $actual = $app->getPlatesPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_root_path_set()
    {
        $expect = __DIR__ . '/Fixture/Sample';

        $app = $this->getSystem();

        $actual = $app->getRootPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_timezone_set()
    {
        $expect = 'Asia/Manila';

        $this->package->setTimezone($expect);

        $app = $this->getSystem();

        $actual = $app->getTimezone();

        $this->assertEquals($expect, $actual);
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
