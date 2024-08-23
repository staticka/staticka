<?php

namespace Staticka\Helpers;

use Rougin\Staticka\Helper\ViewHelper as Staticka;
use Staticka\Contracts\HelperContract;
use Staticka\Contracts\WebsiteContract;

/**
 * @deprecated since ~0.4, use "Rougin\Staticka\Helper\ViewHelper" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelper extends Staticka implements HelperContract
{
    /**
     * @deprecated since ~0.3, initialize with "RendererContract" instead.
     *
     * @param \Staticka\Contracts\RendererContract|\Staticka\Contracts\WebsiteContract $render
     */
    public function __construct($render)
    {
        if ($render instanceof WebsiteContract)
        {
            $render = $render->renderer();
        }

        $this->render = $render;
    }
}
