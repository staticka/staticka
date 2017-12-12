<?php

namespace Rougin\Staticka\Content;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;

/**
 * Markdown Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MarkdownIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $interface = 'Rougin\Staticka\Content\ContentInterface';

        $content = new MarkdownContent;

        return $container->set($interface, $content);
    }
}
