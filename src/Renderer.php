<?php

namespace Staticka;

use Rougin\Slytherin\Template\TwigLoader;
use Rougin\Slytherin\Template\TwigRenderer;
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
     * @var \Rougin\Slytherin\Template\TwigRenderer
     */
    protected $twig;

    /**
     * @param string|string[] $paths
     */
    public function __construct($paths)
    {
        $loader = new TwigLoader;

        $twig = $loader->load($paths);

        $renderer = new TwigRenderer($twig);

        $this->twig = $renderer;
    }

    /**
     * Renders a file from a specified template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     * @param string               $file
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array(), $file = 'php')
    {
        return $this->twig->render($name, $data, $file);
    }
}
