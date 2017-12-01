<?php

namespace Rougin\Staticka\Renderer;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Weasley\Integrations\Illuminate\ViewIntegration;

/**
 * Renderer Integration
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RendererIntegration extends ViewIntegration
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $exists = class_exists('Illuminate\View\Factory');

        $integration = new \Rougin\Slytherin\Template\RendererIntegration;

        $container = $integration->define($container, $config);

        return $exists ? parent::define($container, $config) : $container;
    }
}
