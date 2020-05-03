<?php

namespace Staticka\Helpers;

use Staticka\Contracts\HelperContract;
use Staticka\Contracts\WebsiteContract;
use Staticka\Contracts\RendererContract;

/**
 * View Helper
 *
 * @package Rouginsons
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelper implements HelperContract
{
    /**
     * @var \Staticka\Contracts\RendererContract
     */
    protected $renderer;

    /**
     * TODO: Remove Website instance in v1.0.0.
     * RendererContract should only be used.
     *
     * Initializes the helper instance.
     *
     * @param \Staticka\Contracts\WebsiteContract|\Staticka\Contracts\RendererContract $website
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
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render($name, array $data = array())
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
