<?php

namespace Staticka\Helper;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BlockHelper implements HelperInterface
{
    /**
     * @var string[]
     */
    protected $blocks = array();

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix = LayoutHelper::PREFIX)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function add($name)
    {
        return "<{$this->prefix}-$name></{$this->prefix}-$name>";
    }

    /**
     * @return string
     */
    public function body()
    {
        return $this->set(LayoutHelper::CONTENT);
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->add(LayoutHelper::CONTENT);
    }

    /**
     * @return string
     */
    public function end()
    {
        return '</' . $this->prefix . '-' . end($this->blocks) . '>';
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'block';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function set($name)
    {
        $this->blocks[] = $name;

        return '<' . $this->prefix . '-' . $name . '>';
    }
}
