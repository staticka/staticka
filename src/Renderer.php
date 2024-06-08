<?php

namespace Staticka;

use Staticka\Contracts\RendererContract;
use Zapheus\Renderer\Renderer as ZapheusRenderer;

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
     * @var \Zapheus\Renderer\RendererInterface
     */
    protected $zapheus;

    /**
     * @param string[] $paths
     */
    public function __construct($paths)
    {
        $this->zapheus = new ZapheusRenderer($paths);
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
        return $this->zapheus->render($name, $data);
    }
}
