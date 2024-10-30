<?php

namespace Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Site
{
    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @var \Staticka\Page[]
     */
    protected $pages = array();

    /**
     * @var \Staticka\Parser
     */
    protected $parser;

    /**
     * @param \Staticka\Parser|null $parser
     */
    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ? $parser : new Parser;
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return self
     */
    public function addPage(Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * @param string $output
     *
     * @return self
     */
    public function build($output)
    {
        foreach ($this->pages as $page)
        {
            // Merge site data to page data --------
            $data = $page->getData();

            $data = array_merge($data, $this->data);

            $page = $page->setData($data);
            // -------------------------------------

            $page = $this->parser->parsePage($page);

            $path = $output . '/' . $page->getLink();

            /** @var string */
            $path = str_replace('/index', '', $path);

            $html = $page->getHtml();

            $this->createFile($path, $html);
        }

        return $this;
    }

    /**
     * @param string $source
     * @param string $path
     *
     * @return self
     */
    public function copyDir($source, $path)
    {
        /** @var string */
        $source = realpath($source);

        $this->emptyDir($path);

        /** @var string[] */
        $files = glob("$source/**/**.**");

        foreach ($files as $file)
        {
            /** @var string */
            $real = realpath($file);

            $name = dirname($real);

            $base = basename($real);

            /** @var string */
            $newpath = str_replace($source, $path, $name);

            if (! file_exists($newpath))
            {
                mkdir($newpath, 0777, true);
            }

            copy($file, "$newpath/$base");
        }

        return $this;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function emptyDir($path)
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
            }
            else
            {
                unlink($path);
            }
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param \Staticka\Parser $parser
     *
     * @return self
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @param string $path
     * @param string $html
     *
     * @return void
     */
    protected function createFile($path, $html)
    {
        /** @var string */
        $path = str_replace('/index', '', $path);

        if (! file_exists($path))
        {
            mkdir($path, 0700, true);
        }

        $file = $path . '/index.html';

        file_put_contents($file, $html);
    }
}
