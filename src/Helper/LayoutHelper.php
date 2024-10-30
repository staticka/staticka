<?php

namespace Staticka\Helper;

use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LayoutHelper implements HelperInterface
{
    const CONTENT = 'body';

    const LAYOUT = 'layout';

    const PREFIX = 'ex';

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var \Staticka\Render\RenderInterface
     */
    protected $render;

    /**
     * @param \Staticka\Render\RenderInterface $render
     * @param string                           $prefix
     */
    public function __construct(RenderInterface $render, $prefix = self::PREFIX)
    {
        $this->prefix = $prefix;

        $this->render = $render;
    }

    /**
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function load($name, $data = array())
    {
        $prefix = (string) $this->prefix;

        $helper = new BlockHelper($prefix);

        $data[$helper->name()] = $helper;

        $html = $this->render->render($name, $data);

        $file = $this->prefix . '-' . self::LAYOUT;

        return (string) "<$file>$html</$file>";
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'layout';
    }
}
