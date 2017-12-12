<?php

namespace Rougin\Staticka\Helper;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;

/**
 * Helper Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HelperIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $helper = 'Rougin\Staticka\Helper\UrlHelper';

        $url = new UrlHelper($config->get('app.base_url'));

        return $container->set($helper, $url);
    }
}
