<?php

namespace Rougin\Staticka\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rougin\Staticka\Settings;

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

        $files = $this->files($settings);

        $output->writeln('<info>Currently watching files for changes...</info>');

        while (1) {
            list($length, $updated) = array(count($files), $files);

            for ($i = 0; $i < $length; $i++) {
                $updated[$i]['size'] = filesize($updated[$i]['file']);
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

        $items = array_merge($items, $this->filenames($settings['config']));
        $items = array_merge($items, $this->filenames($settings['content']));
        $items = array_merge($items, $this->filenames($settings['views']));

        foreach ($items as $item) {
            $file = array('file' => $item);

            $file['size'] = filesize($item);

            array_push($files, $file);
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
        $items = array();

        $directory = new \RecursiveDirectoryIterator($source, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 1);

        foreach ($iterator as $file) {
            $filepath = $file->getRealPath();

            array_push($items, $filepath);
        }

        return $items;
    }
}
