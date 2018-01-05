<?php

namespace Staticka\Console\Commands;

use Staticka\Settings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Staticka\Utility;

/**
 * Watch Command
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WatchCommand extends Command
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('watch')->setDescription('Watch changes from the source directory');

        $this->addOption('source', null, 4, 'Source of the site');
        $this->addOption('path', null, 4, 'Path of the site to be built');
        $this->addOption('test', null, 1, 'Option to be use for unit testing');
    }

    /**
     * Runs the "build" command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function build(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('build');

        $inputs = array('--source' => $input->getOption('source'));

        $inputs['--path'] = $input->getOption('path');

        $command->run(new ArrayInput($inputs), $output) && $output->writeln('');
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = Utility::realpath($input->getOption('source'));

        list($counter, $settings) = array(1, new Settings);

        $settings = $settings->load(Utility::path($source . '/staticka.php'));

        $output->writeln('<info>Watching ' . $source . ' for changes..</info>');

        ($files = $this->files($settings)) && $output->writeln('');

        while ($counter === 1) {
            $files = $this->watch($input, $output, $settings, $files);

            sleep(2);

            ! $input->getOption('test') || $counter++;
        }
    }

    /**
     * Returns a listing of available files.
     *
     * @param  \Staticka\Settings $settings
     * @return array
     */
    protected function files(Settings $settings)
    {
        list($files, $items) = array(array(), array());

        foreach ($settings->watchables() as $folder) {
            $names = $this->filenames($folder);

            $items = array_merge($items, $names);
        }

        foreach ((array) $items as $item) {
            $file = array('file' => (string) $item);

            $file['contents'] = file_get_contents($item);

            array_push($files, $file);
        }

        return $files;
    }

    /**
     * Returns a listing of available filenames.
     *
     * @param  string $source
     * @param  array  $items
     * @return array
     */
    protected function filenames($source, $items = array())
    {
        foreach (Utility::files($source, 1) as $file) {
            $filepath = (string) $file->getRealPath();

            $file->isDir() || array_push($items, $filepath);
        }

        return $items;
    }

    /**
     * Watches the specified files for changes.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Staticka\Settings                                $settings
     * @param  array                                             $files
     * @return array
     */
    protected function watch(InputInterface $input, OutputInterface $output, Settings $settings, $files)
    {
        list($length, $updated) = array(count($files), $files);

        $test = $input->getOption('test');

        for ($i = 0; $i < (integer) $length; $i++) {
            $size = file_get_contents($updated[$i]['file']);

            $updated[$i]['contents'] = (string) $size;
        }

        ($files === $updated && ! $test) || $this->build($input, $output);

        return $this->files($settings);
    }
}
