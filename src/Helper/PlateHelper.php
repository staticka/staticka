<?php

namespace Staticka\Helper;

use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PlateHelper implements HelperInterface
{
    /**
     * @var \Staticka\Render\RenderInterface
     */
    protected $render;

    /**
     * @param \Staticka\Render\RenderInterface $render
     */
    public function __construct(RenderInterface $render)
    {
        $this->render = $render;
    }

    /**
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function add($name, $data = array())
    {
        return $this->render->render($name, $data);
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'plate';
    }
}
