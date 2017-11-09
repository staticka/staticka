<?php

namespace Rougin\Staticka;

/**
 * Settings
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Settings
{
    /**
     * @var \Rougin\Staticka\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @param array $settings
     */
    public function __construct(array $settings = array())
    {
        $settings = $settings ?: $this->defaults();

        $config = $settings['config'];

        $settings['config'] = is_string($config) ? array($config) : $config;

        $this->config = new Config($settings['config']);

        $this->settings = $settings;
    }

    /**
     * Returns the \Rougin\Staticka\Config instance.
     *
     * @return \Rougin\Staticka\Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * Returns the content path.
     *
     * @return string
     */
    public function content()
    {
        return $this->settings['content'];
    }

    /**
     * Returns a value based on the specified key.
     *
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        return $this->settings[$key];
    }

    /**
     * Loads the specified file and get its values.
     *
     * @param  string $file
     * @return self
     */
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

    /**
     * Returns a listing of routes or the specified path.
     *
     * @return array|string
     */
    public function routes()
    {
        $routes = $this->settings['routes'];

        if (is_string($routes) == true) {
            $exists = file_exists($routes) === true;

            return $exists ? require $routes : array();
        }

        return $this->settings['routes'];
    }

    /**
     * Returns a views path.
     *
     * @return string
     */
    public function views()
    {
        return $this->settings['views'];
    }

    /**
     * Loads the default values for the "staticka.php" file.
     *
     * @param  string $source
     * @return array
     */
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
