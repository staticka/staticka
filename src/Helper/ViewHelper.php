<?php

namespace Staticka\Helper;

use Staticka\Website;

/**
 * View Helper
 *
 * @package Rouginsons
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ViewHelper implements HelperInterface
{
    /**
     * @var \Staticka\Website
     */
    protected $website;

    /**
     * Initializes the helper instance.
     *
     * @param \Staticka\Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Returns the partial template.
     *
     * @param  string $template
     * @param  array  $data
     * @return string
     */
    public function include($template, array $data = array())
    {
        $data = (array) $this->website->helpers();

        $data['config'] = $this->website;

        $renderer = $this->website->renderer();

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
