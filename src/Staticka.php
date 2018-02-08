<?php

namespace Staticka;

use Staticka\Content\ContentInterface;
use Staticka\Content\MarkdownContent;
use Staticka\Helper\HelperInterface;
use Zapheus\Provider\Configuration;
use Zapheus\Renderer\Renderer;
use Zapheus\Renderer\RendererInterface;

/**
 * Staticka
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Staticka extends Configuration
{
    /**
     * @var \Staticka\Helper\HelperInterface[]
     */
    protected $helpers = array();

    /**
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Staticka\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $output = '';

    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * Initializes the Staticka instance.
     *
     * @param \Staticka\Content\ContentInterface|null   $content
     * @param \Zapheus\Renderer\RendererInterface|null  $renderer
     */
    public function __construct(ContentInterface $content = null, RendererInterface $renderer = null)
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

        $this->clear($output);

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
     * Creates a new page.
     *
     * @param  string      $uri
     * @param  string      $content
     * @param  string|null $template
     * @return self
     */
    public function page($uri, $content, $template = null)
    {
        $this->pages[] = new Page($uri, $content, $template);

        return $this;
    }

    /**
     * Transfers files from a directory into another path.
     *
     * @param  string $source
     * @param  string $path
     * @return void
     */
    public function transfer($source, $path = null)
    {
        $path = $path === null ? $this->output : $path;

        $source = str_replace('/', DIRECTORY_SEPARATOR, $source);

        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        file_exists($path) || mkdir($path);

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
            $directory = $output . '/' . $folder;

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
        $content = (string) $page->content();

        $html = $this->content->make((string) $content);

        if (($name = $page->template()) !== null) {
            $data = (array) $this->helpers;

            $data['config'] = $this;

            $data['content'] = $html;

            $html = $this->renderer->render($name, $data);
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

        $search = array('\\', '/', '\\\\');

        $path = str_replace($search, $separator, $folder);

        file_exists($path) || mkdir((string) $path);

        $exists = in_array(substr($path, -1), $search);

        return $exists ? $path : $path . $separator;
    }
}
