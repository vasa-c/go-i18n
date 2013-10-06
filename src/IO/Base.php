<?php
/**
 * The basic implementation of the IO-interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\IO;

use go\I18n\Exceptions\IOError;

abstract class Base implements IIO
{
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params = null)
    {
        $this->params = $params;
        if ((isset($params['cache'])) && (\is_array($params['cache']))) {
            $cache = $params['cache'];
            if (!isset($cache['files'])) {
                $cache['files'] = array();
            }
            if (!isset($cache['dirs'])) {
                $cache['dirs'] = array();
            }
        } else {
            $cache = array(
                'files' => array(),
                'dirs' => array(),
            );
        }
        $this->cache = $cache;
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $filename
     * @return boolean
     */
    public function isFile($filename)
    {
        if (isset($this->cache['files'][$filename])) {
            return true;
        }
        return $this->doIsFile($filename);
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $dirname
     * @return boolean
     */
    public function isDir($dirname)
    {
        if (isset($this->cache['dirs'][$dirname])) {
            return true;
        }
        return $this->doIsDir($dirname);
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $filename
     * @return int
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getModificationTime($filename)
    {
        if (isset($this->cache['files'][$filename])) {
            $mtime = $this->cache['files'][$filename];
            if (\is_int($mtime)) {
                return $mtime;
            }
        }
        $mtime = $this->doGetModificationTime($filename);
        if ($mtime === false) {
            throw new IOError($filename, 'Get modification time');
        }
        return $mtime;
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $filename
     * @return string
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getContents($filename)
    {
        $contents = $this->doGetContents($filename);
        if ($contents === false) {
            throw new IOError($filename, 'Get contents');
        }
        return $contents;
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $filename
     * @return array
     * @throws \go\I18n\Exceptions\IOError
     */
    public function getContentsByLines($filename)
    {
        $contents = $this->doGetContentsByLines($filename);
        if ($contents === false) {
            throw new IOError($filename, 'Get contents by lines');
        }
        return $contents;
    }

    /**
     * @overrie \go\I18n\IO\IIO
     *
     * @param string $filename
     * @return mixed
     * @throws \go\I18n\Exceptions\IOError
     */
    public function execPhpFile($filename)
    {
        return $this->doExecPhpFile($filename);
    }

    /**
     * @param \go\I18n\IO\IIO $io [optional]
     * @return \go\I18n\IO\IIO
     */
    public static function getIOObject($io = null)
    {
        return $io ?: new Native();
    }

    /**
     * @param string $filename
     * @return boolean
     */
    abstract protected function doIsFile($filename);

    /**
     * @param string $dirname
     * @return boolean
     */
    abstract protected function doIsDir($dirname);

    /**
     * @param string $filename
     * @return int
     * @throws \go\I18n\Exceptions\IOError
     */
    abstract protected function doGetModificationTime($filename);

    /**
     * @param string $filename
     * @return string
     */
    abstract protected function doGetContents($filename);

    /**
     * @param string $filename
     * @return array
     */
    protected function doGetContentsByLines($filename)
    {
        $content = $this->doGetContents($filename);
        if ($content === false) {
            return false;
        }
        $lines = array();
        foreach (\explode("\n", $content) as $line) {
            $line = \trim($line);
            if (!empty($line)) {
                $lines[] = $line;
            }
        }
        return $lines;
    }

    /**
     * @param string $filename
     * @retrun mixed
     */
    abstract protected function doExecPhpFile($filename);

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $cache;
}
