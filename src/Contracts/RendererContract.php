<?php

namespace Staticka\Contracts;

// TODO: To be removed in v1.0.0.
use Zapheus\Renderer\RendererInterface;

/**
 * Renderer Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RendererContract extends RendererInterface
{
    /**
     * Renders a file from a specified template.
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function render($template, $data = array());
}
