<?php

namespace Rougin\Staticka;

use Rougin\Slytherin\Integration\Configuration;

/**
 * Settings
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Settings
{
    /**
     * @var \Rougin\Slytherin\Integration\Configuration
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

        $this->config = new Configuration($settings['config']);

        $this->settings = $settings;
    }

    /**
     * Returns the \Rougin\Slytherin\Integration\Configuration instance.
     *
     * @return \Rougin\Slytherin\Integration\Configuration
     */
    public function config()
    {
        $this->config->set('staticka', $this->settings);

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

        $items = array_merge($default, $items);

        $this->config = new Configuration($items['config']);

        $this->settings = $items;

        return $this;
    }

    /**
     * Returns a listing of routes or the specified path.
     *
     * @return array
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

        list($includes, $items, $settings) = array(array(), array(), array());

        $includes['url'] = 'Rougin\Staticka\Helper\UrlHelper';

        $items[] = 'Rougin\Staticka\Content\MarkdownIntegration';
        $items[] = 'Rougin\Staticka\Helper\HelperIntegration';
        $items[] = 'Rougin\Staticka\Renderer\BladeIntegration';

        $settings['config'] = $source . '/config';
        $settings['content'] = $source . '/content';
        $settings['includes'] = $includes;
        $settings['integrations'] = $items;
        $settings['routes'] = $source . '/routes.php';
        $settings['views'] = $source . '/views';

        return $settings;
    }
}
