<?php

namespace Rougin\Staticka\Console\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rougin\Staticka\Settings;

/**
 * Watch Command
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WatchCommand extends \Symfony\Component\Console\Command\Command
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
     * Initializes the command instance.
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

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
        $this->addOption('test', null, 1, 'Option to be use for unit testing');
    }

    /**
     * Runs the "build" command.
     *
     * @return void
     */
    protected function build()
    {
        $command = $this->getApplication()->find('build');

        $inputs = array('--source' => $this->input->getOption('source'));

        $inputs['--path'] = $this->input->getOption('path');

        $command->run(new ArrayInput($inputs), $this->output);

        $this->output->writeln('');
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($this->input, $this->output) = array($input, $output);

        list($counter, $source) = array(1, realpath($input->getOption('source')));

        $settings = new Settings;

        $settings = $settings->load($source . '/staticka.php');

        $output->writeln('<info>Watching ' . $source . ' for changes...</info>');
        $output->writeln('');

        $files = $this->files($settings);

        while ($counter === 1) {
            $files = $this->watch($settings, $files);

            sleep(2);

            ! $input->getOption('test') || $counter++;
        }
    }

    /**
     * Returns a listing of available files.
     *
     * @param  \Rougin\Staticka\Settings $settings
     * @return array
     */
    protected function files(Settings $settings)
    {
        list($files, $items) = array(array(), array());

        foreach ($settings->watchables() as $folder) {
            $names = $this->filenames($folder);

            $items = array_merge($items, $names);
        }

        foreach ($items as $item) {
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
        $files = \Rougin\Staticka\Utility::files($source, 1);

        foreach ($files as $file) {
            $filepath = $file->getRealPath();

            $file->isDir() || array_push($items, $filepath);
        }

        return $items;
    }

    /**
     * Watches the specified files for changes.
     *
     * @param  \Rougin\Staticka\Settings $settings
     * @param  array                     $files
     * @return array
     */
    protected function watch(Settings $settings, $files)
    {
        list($length, $updated) = array(count($files), $files);

        $test = $this->input->getOption('test');

        for ($i = 0; $i < $length; $i++) {
            $size = file_get_contents($updated[$i]['file']);

            $updated[$i]['contents'] = (string) $size;
        }

        ($files === $updated && ! $test) || $this->build();

        return $this->files($settings);
    }
}
