<?php

namespace Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class System
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
     * @var \Staticka\Parser|null
     */
    protected $parser;

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
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getBuildPath()
    {
        return $this->build;
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->config;
    }

    /**
     * @return \Staticka\Page[]
     */
    public function getPages()
    {
        $path = $this->getPagesPath();

        $parser = $this->getParser();

        /** @var string[] */
        $files = glob($path . '/*.**');

        $pages = array();

        foreach ($files as $file)
        {
            $page = new Page($file);

            $pages[] = $parser->parsePage($page);
        }

        return $pages;
    }

    /**
     * @return string
     */
    public function getPagesPath()
    {
        return $this->pages;
    }

    /**
     * @return \Staticka\Parser
     */
    public function getParser()
    {
        return $this->parser ? $this->parser : new Parser;
    }

    /**
     * @return string
     */
    public function getPlatesPath()
    {
        return $this->plates;
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->root;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
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
     * @param \Staticka\Parser $parser
     *
     * @return self
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;

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
