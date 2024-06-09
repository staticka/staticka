<?php

namespace Staticka\Helpers;

use Staticka\Contracts\HelperContract;
use Staticka\Contracts\WebsiteContract;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelper implements HelperContract
{
    /**
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * @deprecated since ~0.3, initialize with "RendererContract" instead.
     *
     * Initializes the helper instance.
     *
     * @param \Staticka\Contracts\RendererContract|\Staticka\Contracts\WebsiteContract $renderer
     */
    public function __construct($renderer)
    {
        if ($renderer instanceof WebsiteContract)
        {
            $renderer = $renderer->renderer();
        }

        $this->renderer = $renderer;
    }

    /**
     * Renders the partial template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function render($name, $data = array())
    {
        return $this->renderer->render($name, $data);
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'view';
    }
}
