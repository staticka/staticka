<?php

namespace Staticka;

use Staticka\Content\ContentInterface;
use Staticka\Content\MarkdownContent;
use Staticka\Contracts\FilterContract;
use Staticka\Contracts\HelperContract;
use Staticka\Contracts\PageContract;
use Staticka\Contracts\WebsiteContract;
use Staticka\Factories\PageFactory;
use Staticka\Contracts\RendererContract;

/**
 * Website
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Website implements WebsiteContract
{
    /**
     * @var \Staticka\Contracts\BuilderContract
     */
    protected $builder;

    /**
     * @deprecated since ~0.3
     *
     * @var \Staticka\Content\ContentInterface
     */
    protected $content;

    /**
     * @var \Staticka\Factories\PageFactory
     */
    protected $factory;

    /**
     * @deprecated since ~0.3
     *
     * @var \Staticka\Contracts\LayoutContract
     */
    protected $layout;

    /**
     * @deprecated since ~0.3
     *
     * @var string
     */
    protected $output;

    /**
     * @var \Staticka\Contracts\PageContract[]
     */
    protected $pages;

    /**
     * @deprecated since ~0.3
     *
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * @param \Staticka\Contracts\BuilderContract|\Staticka\Contracts\RendererContract|null $builder
     * @param \Staticka\Content\ContentInterface|\Staticka\Contracts\LayoutContract|null    $layout
     */
    public function __construct($builder = null, $layout = null)
    {
        /** @deprecated since ~0.3 */
        $this->content = new MarkdownContent;

        /** @var string */
        $path = getcwd();

        /** @deprecated since ~0.3 */
        $this->renderer = new Renderer(array($path));

        /** @deprecated since ~0.3 */
        if ($builder instanceof RendererContract)
        {
            $this->builder = new Builder($builder);

            $this->renderer = $builder;
        }
        else
        {
            $this->builder = $builder ?: new Builder($this->renderer);
        }

        /** @deprecated since ~0.3 */
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
     *
     * @return self
     */
    public function add(PageContract $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Compiles the specified pages into HTML output.
     *
     * @param string $output
     *
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
     * @param string $path
     *
     * @return void
     */
    public function clear($path)
    {
        $directory = new \RecursiveDirectoryIterator($path, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 2);

        /** @var \SplFileInfo $file */
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
     * @deprecated since ~0.3, use "build" instead.
     *
     * Compiles the specified pages into HTML output.
     *
     * @param string $output
     *
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
     * @deprecated since ~0.3, use "Builder" instead.
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
     * @param string $source
     * @param string $path
     *
     * @return self
     */
    public function copy($source, $path)
    {
        /** @var string */
        $source = realpath($source);

        $this->clear((string) $path);

        /** @var string[] */
        $files = glob("$source/**/**.**");

        foreach ($files as $file)
        {
            /** @var string */
            $real = realpath($file);

            $basename = basename($real);

            $dirname = dirname($real);

            /** @var string */
            $newpath = str_replace($source, $path, $dirname);

            if (! file_exists($newpath))
            {
                mkdir($newpath, 0777, true);
            }

            copy($file, "$newpath/$basename");
        }

        return $this;
    }

    /**
     * Adds a filter instance.
     *
     * @param \Staticka\Contracts\FilterContract $filter
     *
     * @return self
     */
    public function filter(FilterContract $filter)
    {
        $this->layout->filter($filter);

        return $this;
    }

    /**
     * Adds a helper instance.
     *
     * @param \Staticka\Contracts\HelperContract $helper
     *
     * @return self
     */
    public function helper(HelperContract $helper)
    {
        $this->layout->helper($helper);

        return $this;
    }

    /**
     * @deprecated since ~0.3, use "add" instead.
     *
     * Creates a new page.
     *
     * @param string               $file
     * @param array<string, mixed> $data
     *
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
            /** @deprecated since ~0.3, use file-based instead. */
            $this->pages[] = $page->body($file, $data);
        }

        return $this;
    }

    /**
     * @deprecated since ~0.3, use "Builder" instead.
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
     * @deprecated since ~0.3, use "copy" instead.
     *
     * Transfers files from a directory into another path.
     *
     * @param string      $source
     * @param string|null $path
     *
     * @return self
     */
    public function transfer($source, $path = null)
    {
        return $this->copy($source, $path ? $path : $this->output);
    }
}
