<?php

namespace Rougin\Staticka\Console\Commands;

use Rougin\Slytherin\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rougin\Staticka\Generator;
use Rougin\Staticka\Settings;

/**
 * Build Command
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BuildCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * Initializes the command instance.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param string|null                                    $name
     */
    public function __construct(ContainerInterface $container, $name = null)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('build')->setDescription('Build a site from source');

        $this->addOption('source', null, 4, 'Source of the site', getcwd());
        $this->addOption('path', null, 4, 'Path of the site to be built', getcwd() . '/build');
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Consolse\Input\InputInterface   $input
     * @param  \Symfony\Component\Consolse\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site = realpath($input->getOption('source'));

        $build = realpath($input->getOption('path')) ?: $site . '/build';

        $settings = (new Settings)->load($site . '/staticka.php');

        $container = $this->integrate($settings);

        $output->writeln('<info>Building the new site...</info>');

        (new Generator($container, $settings))->make($site, $build);

        $output->writeln('<info>Site built successfully!</info>');
    }

    protected function integrate(Settings $settings)
    {
        $container = $this->container;

        $config = $settings->config();

        foreach ($settings->get('integrations') as $integration) {
            $integration = new $integration;

            $container = $integration->define($container, $config);
        }

        return $container;
    }
}
