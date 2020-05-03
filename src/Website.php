<?php

namespace Staticka;

use Staticka\Content\ContentInterface;
use Staticka\Content\MarkdownContent;
use Staticka\Contracts\BuilderContract;
use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;
use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Contracts\WebsiteContract;
use Staticka\Factories\PageFactory;
use Staticka\Renderer;
use Staticka\Contracts\RendererContract;

/**
 * Website
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Website implements WebsiteContract
{
    /**
     * @var \Staticka\Contracts\BuilderContract
     */
    protected $builder;

    /**
     * TODO: To be removed in v1.0.0.
     *
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Staticka\Factories\PageFactory
     */
    protected $factory;

    /**
     * TODO: To be removed in v1.0.0.
     *
     * @var \Staticka\Contracts\LayoutContract
     */
    protected $layout;

    /**
     * TODO: To be removed in v1.0.0.
     *
     * @var string
     */
    protected $output;

    /**
     * @var \Staticka\Contracts\PageContract[]
     */
    protected $pages;

    /**
     * TODO: To be removed in v1.0.0.
     *
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * TODO: Use contracts in v1.0.0 instead.
     *
     * @param \Staticka\Contracts\RendererContract|\Staticka\Contracts\BuilderContract|null $renderer
     * @param \Staticka\Content\ContentInterface|\Staticka\Contracts\LayoutContract|null  $content
     */
    public function __construct($builder = null, $layout = null)
    {
        // TODO: Remove this after v1.0.0.
        $this->content = new MarkdownContent;

        // TODO: Remove this after v1.0.0.
        $this->renderer = new Renderer(array(getcwd()));

        // TODO: Remove this after v1.0.0.
        if ($builder instanceof RendererContract)
        {
            $this->builder = new Builder($builder);

            $this->renderer = $builder;
        }
        else
        {
            $this->builder = $builder ?: new Builder($this->renderer);
        }

        // TODO: Remove this after v1.0.0.
        if ($layout instanceof ContentInterface)
        {
            $this->content = $layout;

            $this->layout = new Layout;
        }
        else
        {
            $this->layout = $layout ?: new Layout;
        }
    }

    /**
     * Add a new page instance in the website.
     *
     * @param \Staticka\Contracts\PageContract $page
     */
    public function add(PageContract $page)
    {
        $this->pages[] = $page;
    }

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param  string $output
     * @return self
     */
    public function build($output)
    {
        foreach ($this->pages as $page)
        {
            $data = (array) $page->data();

            $link = $data[PageContract::DATA_LINK];

            $folder = $output . '/' . $link;

            $html = $this->builder->build($page);

            $path = str_replace('/index', '', $folder);

            if (! file_exists($path))
            {
                mkdir($path, 0700, true);
            }

            $file = "$folder/index.html";

            if ($link === 'index')
            {
                $file = "$folder.html";
            }

            file_put_contents($file, $html);
        }

        return $this;
    }

    /**
     * Removes the files recursively from the specified directory.
     *
     * @param  string $path
     * @return void
     */
    public function clear($path)
    {
        $directory = new \RecursiveDirectoryIterator($path, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 2);

        foreach ($iterator as $file)
        {
            $path = $file->getRealPath();

            if (strpos($path, '.git') !== false)
            {
                continue;
            }

            if ($file->isDir())
            {
                rmdir($path);

                continue;
            }

            unlink($path);
        }
    }

    /**
     * TODO: To be removed in v1.0.0.
     * Use $this->build() instead.
     *
     * Compiles the specified pages into HTML output.
     *
     * @param  string $output
     * @return self
     */
    public function compile($output)
    {
        if (file_exists($output))
        {
            $this->clear($output);
        }

        $this->output = (string) $output;

        return $this->build($output);
    }

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Returns the content instance.
     *
     * @return \Staticka\Content\ContentInterface
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Transfers files from a directory into another path.
     *
     * @param  string $source
     * @param  string $path
     * @return self
     */
    public function copy($source, $path)
    {
        $source = realpath($source);

        $this->clear((string) $path);

        foreach (glob("$source/**/**.**") as $file)
        {
            $dirname = dirname(realpath($file));

            $basename = basename(realpath($file));

            $newpath = str_replace($source, $path, $dirname);

            mkdir($newpath, 0777, true);

            copy($file, "$newpath/$basename");
        }

        return $this;
    }

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Adds a filter instance.
     *
     * @param  \Staticka\Contracts\FilterContract $filter
     * @return self
     */
    public function filter(FilterContract $filter)
    {
        $this->layout->filter($filter);

        return $this;
    }

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Adds a helper instance.
     *
     * @param  \Staticka\Contracts\HelperContract $helper
     * @return self
     */
    public function helper(HelperContract $helper)
    {
        $this->layout->helper($helper);

        return $this;
    }

    /**
     * TODO: To be removed in v1.0.0.
     * Use $this->add() instead with a factory.
     *
     * Creates a new page.
     *
     * @param  string $file
     * @param  array  $data
     * @return self
     */
    public function page($file, $data = array())
    {
        $page = new PageFactory($this->layout);

        if (file_exists($file))
        {
            $this->pages[] = $page->file($file, $data);
        }
        else
        {
            // TODO: Remove this on v1.0.0.
            // Should only be file-based.

            $this->pages[] = $page->body($file, $data);
        }

        return $this;
    }

    /**
     * TODO: To be removed in v1.0.0.
     *
     * Returns the renderer instance.
     *
     * @return \Staticka\Contracts\RendererContract
     */
    public function renderer()
    {
        return $this->renderer;
    }

    /**
     * TODO: To be removed in v1.0.0.
     * Use $this->copy() instead.
     *
     * Transfers files from a directory into another path.
     *
     * @param  string      $source
     * @param  string|null $path
     * @return self
     */
    public function transfer($source, $path = null)
    {
        return $this->copy($source, $path ? $path : $this->output);
    }
}
