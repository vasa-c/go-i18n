<?php
/**
 * The interface of I\O functions
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\IO;

interface IIO
{
    /**
     * Check if the filename is a regular file
     *
     * @param string $filename
     * @return boolean
     */
    public function isFile($filename);

    /**
     * Check if the dirname is a directory
     *
     * @param string $dirname
     * @return boolean
     */
    public function isDir($dirname);

    /**
     * Get the file last modification time
     *
     * @param string $filename
     * @return int
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getModificationTime($filename);

    /**
     * Read the entire file into a string
     *
     * @param string $filename
     * @return string
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getContents($filename);

    /**
     * Reads the entire file into an array
     *
     * @param string $filename
     * @return array
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getContentsByLine($filename);

    /**
     * Execute the php file and return result
     *
     * @param string $filename
     * @return mixed
     * @throws \go\I18n\Exceptions\IOError
     */
    public function execPhpFile($filename);
}
