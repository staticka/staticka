<?php

namespace Staticka\Contracts;

/**
 * Renderer Contract
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RendererContract
{
    /**
     * Renders a file from a specified template.
     *
     * @param  string $name
     * @param  array  $data
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array());
}
