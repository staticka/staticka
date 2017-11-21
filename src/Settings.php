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

        $this->__construct(array_merge($default, $items));

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

            $routes = $exists ? require $routes : array();
        }

        return $routes;
    }

    /**
     * Returns the before/after script during building.
     *
     * @param  string $type
     * @return string
     */
    public function scripts($type)
    {
        $exists = isset($this->settings['scripts'][$type]);

        $scripts = $exists ? $this->settings['scripts'][$type] : '';

        return is_array($scripts) ? implode(' && ', $scripts) : $scripts;
    }

    /**
     * Returns a listing of watchable directories.
     *
     * @return array
     */
    public function watchables()
    {
        $directories = $this->settings['watch'] ?: array();

        array_push($directories, $this->settings['config']);
        array_push($directories, $this->settings['content']);
        array_push($directories, $this->settings['views']);

        return $directories;
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
        $settings['scripts'] = array('before' => '', 'after' => '');
        $settings['views'] = $source . '/views';
        $settings['watch'] = array();

        return $settings;
    }
}
