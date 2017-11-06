<?php

namespace Rougin\Staticka;

/**
 * Utility
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
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

    /**
     * Returns a listing of files with a \RecursiveIteratorIterator instance.
     *
     * @param  string  $path
     * @param  integer $directory
     * @param  integer $iterator
     * @return \RecursiveIteratorIterator
     */
    public static function files($path, $directory = 4096, $iterator = 1)
    {
        file_exists($path) || mkdir($path);

        $directory = new \RecursiveDirectoryIterator($path, $directory);

        return new \RecursiveIteratorIterator($directory, $iterator);
    }

    /**
     * Replaces the slashes by the DIRECTORY_SEPARATOR.
     *
     * @param  string $path
     * @return string
     */
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
