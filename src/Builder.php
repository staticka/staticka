<?php

namespace Rougin\Staticka;

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
     * @param  string $source
     * @param  string $build
     * @return void
     */
    public function build(array $routes, $source, $build)
    {
        $build = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $build);

        $source = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $source);

        $this->clear($build);

        foreach ($routes as $route) {
            $empty = empty($uris = $route->uri(true));

            $folder = $empty ? '' : $this->folder($build, $uris);

            $path = sprintf('%s/%s/index.html', $build, $folder);

            file_put_contents($path, $this->html($route, $source));
        }

        $this->transfer($source . DIRECTORY_SEPARATOR . 'assets', $build);
    }

    /**
     * Removes the files of the recently built static site.
     *
     * @param  string $path
     * @return void
     */
    protected function clear($path)
    {
        file_exists($path) || mkdir($path);

        $directory = new \RecursiveDirectoryIterator($path, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 2);

        foreach ($iterator as $file) {
            $git = strpos($file->getRealPath(), '.git') !== false;

            $path = $file->getRealPath();

            $git || ($file->isDir() ? rmdir($path) : unlink($path));
        }
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

        $path = $path . '/' . $folder;

        file_exists($path) || mkdir($path);

        return str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $folder);
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

        $data['url'] = new Url($this->config->get('app.base_url'));

        $view = $this->renderer->render($route->view(), $data);

        return $this->minifier->minify($view);
    }

    /**
     * Transfers the specified files into another path.
     *
     * @param  string $source
     * @param  string $path
     * @return void
     */
    protected function transfer($source, $path)
    {
        file_exists($path) || mkdir($path);

        $directory = new \RecursiveDirectoryIterator($source, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 1);

        foreach ($iterator as $file) {
            $to = str_replace($source, $path, $from = $file->getRealPath());

            $file->isDir() ? $this->transfer($from, $to) : copy($from, $to);
        }
    }
}
