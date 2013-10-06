<?php
/**
 * The native implementation of the IO-interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\IO;

class Native extends Base
{
    /**
     * @param string $filename
     * @return boolean
     */
    protected function doIsFile($filename)
    {
        return \is_file($filename);
    }

    /**
     * @param string $dirname
     * @return boolean
     */
    protected function doIsDir($dirname)
    {
        return \is_dir($dirname);
    }

    /**
     * @param string $filename
     * @return int
     */
    protected function doGetModificationTime($filename)
    {
        return @\filemtime($filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function doGetContents($filename)
    {
        return @\file_get_contents($filename);
    }

    /**
     * @param string $filename
     * @return array
     */
    protected function doGetContentsByLines($filename)
    {
        return @\file($filename, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
    }

    /**
     * @param string $filename
     * @retrun mixed
     */
    protected function doExecPhpFile($filename)
    {
        return include($filename);
    }
}
