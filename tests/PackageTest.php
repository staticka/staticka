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
    public function test_build_path()
    {
        $expected = __DIR__ . '/Fixture/Sample/build';

        $app = $this->getSystem();

        $actual = $app->getBuildPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_custom_parser()
    {
        $expected = new Parser;

        $app = $this->getSystem();

        $app->setParser($expected);

        $actual = $app->getParser();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_config_path()
    {
        $expected = __DIR__ . '/Fixture/Sample/config';

        $app = $this->getSystem();

        $actual = $app->getConfigPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_page_items()
    {
        $path = __DIR__ . '/Fixture/Pages';

        $expected = 10;

        $this->package->setPagesPath($path);

        $app = $this->getSystem();

        $actual = $app->getPages();

        $this->assertCount($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_pages_path()
    {
        $expected = __DIR__ . '/Fixture/Sample/pages';

        $app = $this->getSystem();

        $actual = $app->getPagesPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_plates_path()
    {
        $expected = __DIR__ . '/Fixture/Sample/plates';

        $app = $this->getSystem();

        $actual = $app->getPlatesPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_root_path()
    {
        $expected = __DIR__ . '/Fixture/Sample';

        $app = $this->getSystem();

        $actual = $app->getRootPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_set_timezone()
    {
        $expected = 'Asia/Manila';

        $this->package->setTimezone($expected);

        $app = $this->getSystem();

        $actual = $app->getTimezone();

        $this->assertEquals($expected, $actual);
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
