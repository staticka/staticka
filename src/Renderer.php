<?php

namespace Staticka;

use Staticka\Contracts\RendererContract;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererContract
{
    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * Initializes the renderer instance.
     *
     * @param string|string[] $paths
     */
    public function __construct($paths)
    {
        $this->paths = (array) $paths;
    }

    /**
     * Renders a file from a specified template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array())
    {
        $name = str_replace('.', '/', $name);

        $file = null;

        foreach ((array) $this->paths as $key => $path)
        {
            $files = (array) $this->files($path);

            $item = $this->check($files, $path, $key, "$name.php");

            $file = ($item !== null) ? $item : $file;
        }

        if (! is_null($file))
        {
            return $this->extract($file, $data);
        }

        $message = 'Template "' . $name . '" not found.';

        throw new \InvalidArgumentException($message);
    }

    /**
     * Checks if the specified file exists.
     *
     * @param array<string, string> $files
     * @param string                $path
     * @param string                $source
     * @param string                $name
     *
     * @return string|null
     */
    protected function check($files, $path, $source, $name)
    {
        $file = null;

        foreach ($files as $value)
        {
            /** @var string */
            $filepath = str_replace($path, $source, $value);

            /** @var string */
            $filepath = str_replace('\\', '/', $filepath);

            /** @var string */
            $filepath = preg_replace('/^\d\//i', '', $filepath);

            $lowercase = strtolower($filepath) === $name;

            $exists = $filepath === $name;

            $file = ($exists || $lowercase) ? $value : $file;
        }

        return $file;
    }

    /**
     * Extracts the contents of the specified file.
     *
     * @param string               $filepath
     * @param array<string, mixed> $data
     *
     * @return string
     */
    protected function extract($filepath, $data)
    {
        extract($data);

        ob_start();

        include $filepath;

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents ?: '';
    }

    /**
     * Returns an array of filepaths from a specified directory.
     *
     * @param string $path
     *
     * @return string[]
     */
    protected function files($path)
    {
        $directory = new \RecursiveDirectoryIterator($path);

        $iterator = new \RecursiveIteratorIterator($directory);

        $pattern = '/^.+\.php$/i';

        $regex = new \RegexIterator($iterator, $pattern, 1);

        return array_keys(iterator_to_array($regex));
    }
}
