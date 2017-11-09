<?php

namespace Rougin\Staticka;

use Rougin\Staticka\Helpers\Utility;

/**
 * Builder
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Builder
{
    /**
     * @var \Rougin\Staticka\Config
     */
    protected $config;

    /**
     * @var \Rougin\Staticka\Converter
     */
    protected $converter;

    /**
     * @var \Rougin\Staticka\Minifier
     */
    protected $minifier;

    /**
     * @var \Rougin\Staticka\Renderer
     */
    protected $renderer;

    /**
     * Initializes the builder instance.
     *
     * @param \Rougin\Staticka\Config   $config
     * @param \Rougin\Staticka\Renderer $renderer
     */
    public function __construct(Config $config, Renderer $renderer)
    {
        $this->config = $config;

        $this->converter = new Converter;

        $this->minifier = new Minifier;

        $this->renderer = $renderer;
    }

    /**
     * Builds the static site based on the given routes.
     *
     * @param  array  $routes
     * @param  string $from
     * @param  string $build
     * @return void
     */
    public function build(array $routes, $from, $to)
    {
        list($to, $from) = array(Utility::path($to), Utility::path($from));

        Utility::clear($to);

        foreach ($routes as $route) {
            $empty = empty($uris = $route->uri(true));

            $folder = $empty ? '' : $this->folder($to, $uris);

            $path = sprintf('%s/%s/index.html', $to, $folder);

            file_put_contents($path, $this->html($route, $from));
        }

        Utility::transfer(Utility::path($from . '/assets'), $to);
    }

    /**
     * Returns the whole folder path based from specified URIs.
     *
     * @param  string $path
     * @param  array  $uris
     * @param  string $folder
     * @return string
     */
    protected function folder($path, $uris, $folder = '')
    {
        foreach ($uris as $uri) {
            $folder = (empty($folder)) ? $uri : $folder;

            $directory = $path . '/' . $folder;

            file_exists($directory) || mkdir($directory);

            ($folder === $uri) || $folder .= '/' . $uri;
        }

        file_exists($path .= '/' . $folder) || mkdir($path);

        return Utility::path($folder);
    }

    /**
     * Converts the specified route into a minified HTML.
     *
     * @param  \Rougin\Staticka\Route $route
     * @param  string                 $source
     * @return string
     */
    protected function html(Route $route, $source)
    {
        $file = sprintf($source . '/content/%s.md', $route->content());

        $content = $this->converter->convert(file_get_contents($file));
        $data = array('content' => $content, 'config' => $this->config);
        $includes = $this->config->get('app.includes', array());
        $data = array_merge($data, $includes);

        $data['url'] = new Helpers\Url($this->config->get('app.base_url'));

        $view = $this->renderer->render($route->view(), $data);

        return $this->minifier->minify($view);
    }
}
