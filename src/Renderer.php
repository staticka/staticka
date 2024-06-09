<?php

namespace Staticka;

use Rougin\Slytherin\Template\Renderer as Slytherin;
use Staticka\Contracts\RendererContract;

/**
 * TODO: Use own implementation for RendererContract.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererContract
{
    /**
     * @var \Rougin\Slytherin\Template\Renderer
     */
    protected $renderer;

    /**
     * @param string|string[] $paths
     */
    public function __construct($paths)
    {
        $this->renderer = new Slytherin($paths);
    }

    /**
     * Renders a file from a specified template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array())
    {
        return $this->renderer->render($name, $data);
    }
}
