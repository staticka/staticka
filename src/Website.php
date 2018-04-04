<?php

namespace Staticka;

use Staticka\Content\ContentInterface;
use Staticka\Content\MarkdownContent;
use Staticka\Filter\FilterInterface;
use Staticka\Helper\HelperInterface;
use Zapheus\Provider\Configuration;
use Zapheus\Renderer\Renderer;
use Zapheus\Renderer\RendererInterface;

/**
 * Website
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Website extends Configuration
{
    /**
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Staticka\Helper\HelperInterface[]
     */
    protected $helpers = array();

    /**
     * @var \Staticka\Filter\FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var string
     */
    protected $output = '';

    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * @var \Zapheus\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * Initializes the Staticka instance.
     *
     * @param \Zapheus\Renderer\RendererInterface|null $renderer
     * @param \Staticka\Content\ContentInterface|null  $content
     */
    public function __construct(RendererInterface $renderer = null, ContentInterface $content = null)
    {
        $this->renderer = $renderer === null ? new Renderer(getcwd()) : $renderer;

        $this->content = $content === null ? new MarkdownContent : $content;
    }

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param  string $output
     * @return self
     */
    public function compile($output)
    {
        file_exists($output) || mkdir($output);

        $this->clear((string) $output);

        $this->output = (string) $output;

        foreach ((array) $this->pages as $page) {
            $folder = $this->folder($output, $page->uris());

            $html = (string) $this->html($page);

            $path = $this->path($output . '/' . $folder);

            file_put_contents($path . 'index.html', $html);
        }

        return $this;
    }

    /**
     * Returns the content instance.
     *
     * @return \Staticka\Content\ContentInterface
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Adds a filter instance.
     *
     * @param  \Staticka\Filter\FilterInterface $filter
     * @return self
     */
    public function filter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Adds a helper instance.
     *
     * @param  \Staticka\Helper\HelperInterface $helper
     * @return self
     */
    public function helper(HelperInterface $helper)
    {
        $this->helpers[$helper->name()] = $helper;

        return $this;
    }

    /**
     * Returns an array of helpers.
     *
     * @return \Staticka\Helper\HelperInterface[]
     */
    public function helpers()
    {
        return $this->helpers;
    }

    /**
     * Creates a new page.
     *
     * @param  string $file
     * @param  array  $data
     * @return self
     */
    public function page($file, array $data = array())
    {
        $this->pages[] = new Page($file, $data);

        return $this;
    }

    /**
     * Returns the renderer instance.
     *
     * @return \Zapheus\Renderer\RendererInterface
     */
    public function renderer()
    {
        return $this->renderer;
    }

    /**
     * Transfers files from a directory into another path.
     *
     * @param  string      $source
     * @param  string|null $path
     * @return void
     */
    public function transfer($source, $path = null)
    {
        $path = $path === null ? $this->output : (string) $path;

        $source = str_replace('/', DIRECTORY_SEPARATOR, $source);

        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        $directory = new \RecursiveDirectoryIterator($source, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 1);

        foreach ($iterator as $file) {
            $to = str_replace($source, $path, $from = $file->getRealPath());

            $file->isDir() ? $this->transfer($from, $to) : copy($from, $to);
        }
    }

    /**
     * Removes the files recursively from the specified directory.
     *
     * @param  string $path
     * @return void
     */
    protected function clear($path)
    {
        $directory = new \RecursiveDirectoryIterator($path, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 2);

        foreach ($iterator as $file) {
            $git = strpos($file->getRealPath(), '.git') !== false;

            $path = (string) $file->getRealPath();

            $git || ($file->isDir() ? rmdir($path) : unlink($path));
        }
    }

    /**
     * Returns the whole folder path based from specified URIs.
     * Also creates the specified folder if it doesn't exists.
     *
     * @param  string $output
     * @param  array  $uris
     * @return string
     */
    protected function folder($output, array $uris)
    {
        $folder = (string) '';

        foreach ((array) $uris as $uri) {
            $directory = $output . '/' . (string) $folder;

            file_exists($directory) ?: mkdir($directory);

            $folder === $uri ?: $folder .= '/' . $uri;
        }

        return $folder;
    }

    /**
     * Converts the specified page into HTML.
     *
     * @param  \Staticka\Page $page
     * @return string
     */
    protected function html(Page $page)
    {
        $html = $this->content->make($content = $page->content());

        if (($name = $page->layout()) !== null) {
            $data = array_merge($this->helpers(), (array) $page->data());

            $layout = new Layout($this->renderer, $this, $data);

            $html = (string) $layout->render($name, (string) $content);
        }

        foreach ($this->filters as $filter) {
            $html = $filter->filter($html);
        }

        return $html;
    }

    /**
     * Replaces the slashes with the DIRECTORY_SEPARATOR.
     * Also creates the directory if it doesn't exists.
     *
     * @param  string $folder
     * @return string
     */
    protected function path($folder)
    {
        $separator = (string) DIRECTORY_SEPARATOR;

        $search = array('\\', '/', (string) '\\\\');

        $path = str_replace($search, $separator, $folder);

        file_exists($path) || mkdir((string) $path);

        $exists = in_array(substr($path, -1), $search);

        return $exists ? $path : $path . $separator;
    }
}
