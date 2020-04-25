<?php

namespace Staticka\Factories;

use Staticka\Contracts\LayoutContract;
use Staticka\Contracts\PageContract;
use Staticka\Matter;
use Staticka\Page;
use Symfony\Component\Yaml\Yaml;

class PageFactory
{
    protected $layout;

    public function __construct(LayoutContract $layout)
    {
        $this->layout = $layout;
    }

    /**
     * TODO: To be removed on v1.0.0.
     * Should only be file-based.
     *
     * Creates a new page instance based on the content.
     *
     * @param  string $body
     * @param  array  $data
     * @return \Staticka\Contracts\PageContract
     */
    public function body($body, $data = array())
    {
        $data[PageContract::DATA_BODY] = (string) $body;

        if (! isset($data[PageContract::DATA_LINK]))
        {
            $data[PageContract::DATA_LINK] = 'index';
        }

        return $this->make((array) $data);
    }

    public function file($file, $data = array())
    {
        // TODO: Remove this on v1.0.0.
        // Use $this->parse() instead.

        $result = (string) file_get_contents($file);

        list($result, $content) = $this->parse($result);

        $data = array_merge($result, $data);

        $data[PageContract::DATA_BODY] = (string) trim($content);

        // TODO: Use this on v1.0.0.
        // $data = $this->parse(file_get_contents($file));

        $info = (array) pathinfo((string) $file);

        if (! isset($data[PageContract::DATA_LINK]))
        {
            $data[PageContract::DATA_LINK] = $info['filename'];
        }

        return $this->make($data);
    }

    protected function make($data)
    {
        // TODO: Remove this on v1.0.0.
        // Use DATA_PATH instead of DATA_LAYOUT.
        if (isset($data[PageContract::DATA_LAYOUT]))
        {
            $data[PageContract::DATA_PATH] = $data[PageContract::DATA_LAYOUT];
        }

        return new Page($this->layout, $data);
    }

    protected function parse($content)
    {
        return Matter::parse($content);

        // $matter = array();

        // $text = str_replace(PHP_EOL, $id = uniqid(), $content);

        // $regex = '/^---' . $id . '(.*?)' . $id . '---/';

        // if (preg_match($regex, $text, $matches) === 1)
        // {
        //     $yaml = str_replace($id, PHP_EOL, $matches[1]);

        //     $matter = (array) Yaml::parse(trim($yaml));

        //     $body = str_replace($matches[0], '', $text);

        //     $content = str_replace($id, PHP_EOL, $body);
        // }

        // $matter[PageContract::DATA_BODY] = trim($content);

        // return (array) $matter;
    }
}
