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

        $config = new Configuration($settings['config']);

        $view = array('illuminate.view.compiled', 'illuminate.view.templates');

        $config->set('app.views', $config->get($view[1], $settings['views']));
        $config->set($view[0], $config->get($view[0], sys_get_temp_dir()));
        $config->set($view[1], $config->get($view[1], $settings['views']));

        $this->config = $config;

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
     * @return mixed
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

        if (is_string($routes) === true) {
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

        $settings = array('watch' => array());

        $settings['config'] = $source . '/config';
        $settings['content'] = $source . '/content';
        $settings['filters'] = $this->filters();
        $settings['includes'] = $this->includes();
        $settings['integrations'] = $this->integrations();
        $settings['routes'] = $source . '/routes.php';
        $settings['scripts'] = array('before' => '', 'after' => '');
        $settings['views'] = $source . '/views';

        return $settings;
    }

    /**
     * Returns a listing of default filters.
     *
     * @return array
     */
    protected function filters()
    {
        $filters = array();

        array_push($filters, 'Rougin\Staticka\Filter\CssMinifier');
        array_push($filters, 'Rougin\Staticka\Filter\HtmlMinifier');

        return $filters;
    }

    /**
     * Returns a listing of default includes.
     *
     * @return array
     */
    protected function includes()
    {
        $includes = array();

        $includes['url'] = 'Rougin\Staticka\Helper\UrlHelper';

        return $includes;
    }

    /**
     * Returns a listing of default integrations.
     *
     * @return array
     */
    protected function integrations()
    {
        $integrations = array();

        $integrations[] = 'Rougin\Staticka\Content\MarkdownIntegration';
        $integrations[] = 'Rougin\Staticka\Helper\HelperIntegration';
        $integrations[] = 'Rougin\Staticka\Renderer\RendererIntegration';

        return $integrations;
    }
}
