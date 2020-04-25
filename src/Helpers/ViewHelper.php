<?php

namespace Staticka\Helpers;

use Staticka\Contracts\HelperContract;
use Staticka\Website;

/**
 * View Helper
 *
 * @package Rouginsons
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelper implements HelperContract
{
    /**
     * @var \Staticka\Website
     */
    protected $website;

    /**
     * TODO: Remove Website instance in v1.0.0.
     * RendererContract should only be used.
     *
     * Initializes the helper instance.
     *
     * @param \Staticka\Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Renders the partial template.
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     */
    public function render($template, array $data = array())
    {
        $layout = $this->website->layout();

        $builder = $this->website->builder();

        $data = array_merge($data, $layout->helpers());

        $data['config'] = $this->website;

        $renderer = $builder->renderer();

        return $renderer->render($template, $data);
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
