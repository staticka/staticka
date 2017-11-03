<?php

namespace Rougin\Staticka\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends \Symfony\Component\Console\Command\Command
{
    public function configure()
    {
        $this->setName('build')->setDescription('Build a site from source');

        $this->addOption('source', null, 4, 'Source of the site', getcwd());
        $this->addOption('path', null, 4, 'Path of the site to be built', getcwd() . '/build');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $build = realpath($input->getOption('path')) ?: getcwd() . '/build';

        $site = realpath($input->getOption('source'));

        file_exists($site . '/views') || $this->exception($site);

        $config = new \Rougin\Staticka\Config($site . '/config');

        $renderer = new \Rougin\Staticka\Renderer($site . '/views');

        $builder = new \Rougin\Staticka\Builder($config, $renderer);

        $builder->build(require $site . '/routes.php', $site, $build);

        $output->writeln('<info>Site built successfully!</info>');
    }

    protected function exception($source)
    {
        $format = 'Source directory "%s" %s!';

        if (file_exists($source) === false) {
            $message = sprintf($format, $source, 'does not exists');

            throw new \InvalidArgumentException($message);
        }

        if (file_exists($source . '/views') === false) {
            $error = 'don\'t have any content';

            $message = sprintf($format, $source, $error);

            throw new \InvalidArgumentException($message);
        }
    }
}
