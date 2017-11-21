<?php

namespace Rougin\Staticka\Console\Commands;

use Rougin\Slytherin\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rougin\Staticka\Generator;
use Rougin\Staticka\Settings;
use Rougin\Staticka\Utility;

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
     * @var \Rougin\Staticka\Settings
     */
    protected $settings;

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

        $this->settings = new Settings;
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
        $this->addOption('test', null, 1, 'Option to be use for unit testing');
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

        $settings = $this->settings->load($site . '/staticka.php');

        $output->writeln('<info>Building the new site...</info>');

        $this->script($site, $settings->scripts('before'), $output);

        $generator = new Generator($this->integrate($settings), $settings);

        $generator->make($site, $build);

        Utility::transfer(Utility::path($site . '/assets'), $build);

        $this->script($site, $settings->scripts('after'), $output);

        $output->writeln('<info>Site built successfully!</info>');
    }

    /**
     * Displays the script and run it using exec().
     *
     * @param  string                                             $source
     * @param  string                                             $scripts
     * @param  \Symfony\Component\Consolse\Output\OutputInterface $output
     * @return void
     */
    protected function script($source, $scripts, OutputInterface $output)
    {
        $message = 'Running script "' . $scripts . '"...';

        ! $scripts || $output->writeln('<info>' . $message . '</info>');

        ! $scripts || exec('cd ' . $source . ' && ' . $scripts);
    }

    /**
     * Adds all defined integrations inside the container.
     *
     * @param  \Rougin\Staticka\Settings $settings
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
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
