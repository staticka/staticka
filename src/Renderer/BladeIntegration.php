<?php

namespace Rougin\Staticka\Renderer;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Template\Renderer;

/**
 * Blade Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BladeIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $renderer = new Renderer($config->get('staticka.views', array()));

        if (class_exists('Illuminate\View\View')) {
            $templates = $config->get('staticka.views', array());

            $compiled = sys_get_temp_dir();

            $renderer = new BladeRenderer((array) $templates, $compiled);
        }

        $container->set('Rougin\Slytherin\Template\RendererInterface', $renderer);

        return $container;
    }
}
