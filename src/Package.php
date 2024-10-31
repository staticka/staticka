<?php

namespace Staticka;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Package implements IntegrationInterface
{
    /**
     * @var string
     */
    protected $build;

    /**
     * @var string
     */
    protected $config;

    /**
     * @var string
     */
    protected $pages;

    /**
     * @var string
     */
    protected $plates;

    /**
     * @var string
     */
    protected $root;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @param string      $root
     * @param string|null $timezone
     */
    public function __construct($root, $timezone = null)
    {
        $default = date_default_timezone_get();

        $this->root = $root;

        $timezone = $timezone ? $timezone : $default;

        $this->setTimezone($timezone);
    }

    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $app = new System($this->root);

        $app->setBuildPath($this->build);

        $app->setConfigPath($this->config);

        $app->setPagesPath($this->pages);

        $app->setPlatesPath($this->plates);

        $app->setTimezone($this->timezone);

        return $container->set(get_class($app), $app);
    }

    /**
     * @param string $build
     *
     * @return self
     */
    public function setBuildPath($build)
    {
        $this->build = $build;

        return $this;
    }

    /**
     * @param string $config
     *
     * @return self
     */
    public function setConfigPath($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return self
     */
    public function setPathsFromRoot()
    {
        $this->setBuildPath($this->root . '/build');

        $this->setConfigPath($this->root . '/config');

        $this->setPagesPath($this->root . '/pages');

        $this->setPlatesPath($this->root . '/plates');

        return $this;
    }

    /**
     * @param string $pages
     *
     * @return self
     */
    public function setPagesPath($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * @param string $plates
     *
     * @return self
     */
    public function setPlatesPath($plates)
    {
        $this->plates = $plates;

        return $this;
    }

    /**
     * @param string $timezone
     *
     * @return self
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }
}
