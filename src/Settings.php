<?php

namespace Rougin\Staticka;

class Settings
{
    protected $config;

    protected $settings = array();

    public function __construct(array $settings = array())
    {
        $settings = $settings ?: $this->defaults();

        $this->config = new Config($settings['config']);

        $this->settings = $settings;
    }

    public function config()
    {
        return $this->config;
    }

    public function content()
    {
        return $this->content;
    }

    public function routes()
    {
        return $this->settings['routes'];
    }

    public function views()
    {
        return $this->settings['views'];
    }

    protected function defaults()
    {
        $settings = array();

        $settings['config'] = getcwd() . '/config';
        $settings['content'] = getcwd() . '/content';
        $settings['routes'] = array();
        $settings['views'] = getcwd() . '/views';

        return $settings;
    }
}