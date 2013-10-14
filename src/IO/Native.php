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
        $result = @\file($filename);
        if (!\is_array($result)) {
            return false;
        }
        $lines = array();
        foreach ($result as $line) {
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
    protected function doExecPhpFile($filename)
    {
        return include($filename);
    }

    /**
     * @param string $dirname
     * @param boolean $recursive
     * @param boolean $mtime
     * @return array
     */
    protected function doGetDirectoryContents($dirname, $recursive, $mtime)
    {
        if ($this->cache['full']) {
            return $this->loadDirectoryFromCache($dirname, $recursive, $mtime);
        }
        $result = array(
            'files' => array(),
            'dirs' => array(),
        );
        $this->loadFromDir($dirname, $recursive, $mtime, $result);
        return $result;
    }

    /**
     * @param string $dirname
     * @param boolean $mtime
     * @return array
     */
    private function loadDirectoryFromCache($dirname, $recursive, $mtime)
    {
        $keys = array('dirs', 'files');
        $result = array();
        $slen = \strlen($dirname);
        foreach ($keys as $key) {
            $result[$key] = array();
            foreach ($this->cache[$key] as $fn => $mt) {
                if (\strpos($fn, $dirname) !== 0) {
                    continue;
                }
                if (\strlen($fn) === $slen) {
                    continue;
                }
                if (!$recursive) {
                    if (\strpos($fn, '/', $slen + 1) || \strpos($fn, '\\', $slen + 1)) {
                        continue;
                    }
                }
                if ($mtime && (!\is_int($mt)) && ($key === 'files')) {
                    $mt = $this->getModificationTime($fn);
                }
                $result[$key][$fn] = $mt;
            }
        }
        return $result;
    }

    /**
     * @param string $dirname
     * @param boolean $recursive
     * @param boolean $mtime
     * @param array $result
     */
    private function loadFromDir($dirname, $recursive, $mtime, &$result)
    {
        $dir = new \DirectoryIterator($dirname);
        foreach ($dir as $item) {
            $filename = $dir->getPathname();
            if ($dir->isDir()) {
                if ($dir->isDot()) {
                    continue;
                }
                $result['dirs'][$filename] = true;
                if ($recursive) {
                    $this->loadFromDir($filename, $recursive, $mtime, $result);
                }
            } else {
                if ($mtime) {
                    $mt = $this->getModificationTime($filename);
                } else {
                    $mt = true;
                }
                $result['files'][$filename] = $mt;
            }
        }
    }
}
