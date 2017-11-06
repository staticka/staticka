<?php

namespace Rougin\Staticka\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $build = realpath($input->getOption('path')) ?: getcwd() . '/build';
        $site = realpath($input->getOption('source'));

        $settings = new Settings(require $site . '/staticka.php');

        file_exists($settings->views()) || $this->exception($settings, $site);

        $renderer = new \Rougin\Staticka\Renderer($settings->views());
        $builder = new \Rougin\Staticka\Builder($settings->config(), $renderer);

        $output->writeln('<info>Building the new site...</info>');

        $builder->build($settings->routes(), $site, $build);

        $output->writeln('<info>Site built successfully!</info>');
        $output->writeln('');
    }

    /**
     * Returns an exception if there is an error.
     *
     * @param  \Rougin\Staticka\Settings $settings
     * @param  string                    $source
     */
    protected function exception(Settings $settings, $source)
    {
        $format = 'Source directory "%s" %s!';

        if (file_exists($source) === false) {
            $message = sprintf($format, $source, 'does not exists');

            throw new \InvalidArgumentException($message);
        }

        if (file_exists($settings->views()) === false) {
            $error = 'don\'t have any content';

            $message = sprintf($format, $source, $error);

            throw new \InvalidArgumentException($message);
        }
    }
}
