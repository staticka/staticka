<?php

namespace Rougin\Staticka;

use Psr\Container\ContainerInterface;

/**
 * Generator
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Generator
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Rougin\Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Rougin\Staticka\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var \Rougin\Staticka\Settings
     */
    protected $settings;

    /**
     * Initializes the generator instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param \Rougin\Staticka\Settings         $settings
     */
    public function __construct(ContainerInterface $container, Settings $settings)
    {
        $this->container = $container;

        $this->content = $container->get('Rougin\Staticka\Content\ContentInterface');

        $this->renderer = $container->get('Rougin\Slytherin\Template\RendererInterface');

        $this->settings = $settings;
    }

    /**
     * Generates the specified contents to HTML files.
     *
     * @param  string $from
     * @param  string $to
     * @return void
     */
    public function make($from, $to)
    {
        Utility::clear($to);

        list($to, $from) = array(Utility::path($to), Utility::path($from));

        foreach ($this->settings->routes() as $route) {
            $uris = $route->uri(true);

            $folder = empty($uris) ? '' : $this->folder($to, $uris);

            $path = Utility::path($to . '/' . $folder);

            file_exists($path) || mkdir($path);

            $html = $this->html($route, $from);

            file_put_contents($path . '/index.html', $html);
        }
    }

    /**
     * Returns the whole folder path based from specified URIs.
     * Also creates the specified folder if it doesn't exists.
     *
     * @param  string $path
     * @param  array  $uris
     * @param  string $folder
     * @return string
     */
    protected function folder($path, $uris, $folder = '')
    {
        foreach ($uris as $uri) {
            $directory = $path . '/' . $folder;

            file_exists($directory) ?: mkdir($directory);

            $folder === $uri ?: $folder .= '/' . $uri;
        }

        return $folder;
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
        $data = array('config' => $this->settings->config());

        $file = sprintf($source . '/content/%s', $route->content());

        $includes = $this->settings->get('includes');

        foreach ($includes as $key => $value) {
            $item = $this->container->get($value);

            $data[$key] = $item;
        }

        $content = $this->content->convert(file_get_contents($file));

        $data['content'] = $content;

        return $this->renderer->render($route->view(), $data);
    }
}
