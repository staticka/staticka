<?php

namespace Rougin\Staticka\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WatchCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('watch')->setDescription('Watch changes from source');

        $this->addOption('source', null, 4, 'Source of the site', getcwd());
        $this->addOption('path', null, 4, 'Path of the site to be built', getcwd() . '/build');
    }

    /**
     * Runs the "build" command.
     *
     * @param  \Symfony\Component\Consolse\Input\InputInterface   $input
     * @param  \Symfony\Component\Consolse\Output\OutputInterface $output
     */
    protected function build(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('build');

        $inputs = array('command' => 'watch');

        $inputs['--source'] = $input->getOption('source');
        $inputs['--path'] = $input->getOption('path');

        $array = new \Symfony\Component\Console\Input\ArrayInput($inputs);

        $command->run($input, $output);
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Consolse\Input\InputInterface   $input
     * @param  \Symfony\Component\Consolse\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = realpath($input->getOption('source'));

        $settings = require $source . '/staticka.php';

        $output->writeln("<info>Watching \"$source\" for changes...</info>");
        $output->writeln('');

        $files = $this->files($settings);

        while (1) {
            list($length, $updated) = array(count($files), $files);

            for ($i = 0; $i < $length; $i++) {
                $size = file_get_contents($updated[$i]['file']);

                $updated[$i]['contents'] = $size;
            }

            $files === $updated || $this->build($input, $output);

            $files = $this->files($settings);

            sleep(2);
        }
    }

    /**
     * Returns a listing of available files.
     *
     * @param  array $settings
     * @return array
     */
    protected function files(array $settings)
    {
        list($files, $items) = array(array(), array());

        $config = $this->filenames($settings['config']);
        $content = $this->filenames($settings['content']);
        $views = $this->filenames($settings['views']);

        $items = array_merge($items, $config, $content, $views);

        foreach ($items as $item) {
            $file = array('file' => (string) $item);

            $file['contents'] = file_get_contents($item);

            array_push($files, array('file' => $item));
        }

        return $files;
    }

    /**
     * Returns a listing of available filenames.
     *
     * @param  string $source
     * @return array
     */
    protected function filenames($source)
    {
        $files = \Rougin\Staticka\Utility::files($source, 4096, 1);

        $items = array();

        foreach ($files as $file) {
            $filepath = $file->getRealPath();

            $file->isDir() || array_push($items, $filepath);
        }

        return $items;
    }
}