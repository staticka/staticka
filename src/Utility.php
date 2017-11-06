<?php

namespace Rougin\Staticka;

class Utility
{
    /**
     * Removes the files of the recently built static site.
     *
     * @param  string $path
     * @return void
     */
    public static function clear($path)
    {
        foreach (Utility::files($path, 4096, 2) as $file) {
            $git = strpos($file->getRealPath(), '.git') !== false;

            $path = $file->getRealPath();

            $git || ($file->isDir() ? rmdir($path) : unlink($path));
        }
    }

    public static function files($path, $directory = 4096, $iterator = 1)
    {
        file_exists($path) || mkdir($path);

        $directory = new \RecursiveDirectoryIterator($path, 4096);

        return new \RecursiveIteratorIterator($directory, 2);
    }

    public static function path($path)
    {
        return str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Transfers the specified files into another path.
     *
     * @param  string $source
     * @param  string $path
     * @return void
     */
    public static function transfer($source, $path)
    {
        file_exists($path) || mkdir($path);

        foreach (self::files($source, 4096, 1) as $file) {
            $to = str_replace($source, $path, $from = $file->getRealPath());

            $file->isDir() ? self::transfer($from, $to) : copy($from, $to);
        }
    }
}
