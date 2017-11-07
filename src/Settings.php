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
        return $this->settings['content'];
    }

    public function get($key)
    {
        return $this->settings[$key];
    }

    public function load($file = null)
    {
        $file = $file ?: getcwd() . '/staticka.php';

        $source = str_replace('/staticka.php', '', $file);

        $default = $this->defaults($source);

        $items = file_exists($file) ? require $file : $default;

        $this->config = new Config($items['config']);

        $this->settings = $items;

        return $this;
    }

    public function routes()
    {
        $routes = $this->settings['routes'];

        if (is_string($routes) == true) {
            $exists = file_exists($routes) === true;

            return $exists ? require $routes : array();
        }

        return $this->settings['routes'];
    }

    public function views()
    {
        return $this->settings['views'];
    }

    protected function defaults($source = null)
    {
        $source = $source ?: (string) getcwd();

        $settings = array();

        $settings['config'] = $source . '/config';
        $settings['content'] = $source . '/content';
        $settings['routes'] = $source . '/routes.php';
        $settings['views'] = $source . '/views';

        return $settings;
    }
}
