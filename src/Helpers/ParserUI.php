<?php
/**
 * The parser of .ui-files
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\FormatFile;

class ParserUI
{
    /**
     * @param string $filename
     * @param \go\I18n\IO\IIO $io [optional]
     * @return array
     * @throws \go\I18n\Exceptions\IOError
     * @throws \go\I18n\Exceptions\FormatFile
     */
    public static function parseFile($filename, \go\I18n\IO\IIO $io = null)
    {
        $io = \go\I18n\IO\Base::getIOObject($io);
        return self::parseLines($io->getContentsByLines($filename), $filename);
    }

    /**
     * @param string $content
     * @param string $filename [optional]
     * @return array
     * @throws \go\I18n\Exceptions\FormatFile
     */
    public static function parseContent($content, $filename = null)
    {
        $lines = array();
        foreach (\explode("\n", $content) as $line) {
            $line = \trim($line);
            if (!empty($line)) {
                $lines[] = $line;
            }
        }
        return self::parseLines($lines, $filename);
    }

    /**
     * @param array $lines
     * @param string $filename [optional]
     * @return array
     * @throws \go\I18n\Exceptions\FormatFile
     */
    public static function parseLines(array $lines, $filename = null)
    {
        $result = array();
        $current = &$result;
        foreach ($lines as $line) {
            $first = $line[0];
            if ($first === '#') {
                continue;
            }
            if ($first === '[') {
                if (!\preg_match('~^\[([a-z0-9_.-]*)\]$~is', $line, $matches)) {
                    return self::error($filename, $line);
                }
                $section = \trim($matches[1]);
                $current = &$result;
                if (!empty($section)) {
                    $section = \explode('.', $section);
                    foreach ($section as $s) {
                        if (empty($s)) {
                            return self::error($filename, $line);
                        }
                        if ((!isset($current[$s])) || (!\is_array($current[$s]))) {
                            $current[$s] = array();
                        }
                        $current = &$current[$s];
                    }
                }
            } else {
                $nv = \explode(':', $line, 2);
                if (\count($nv) !== 2) {
                    return self::error($filename, $line);
                }
                $name = \trim($nv[0]);
                $value = \trim($nv[1]);
                if (\preg_match('~^(.*?)\[([^\]]*)\]$~s', $name, $matches)) {
                    $name = $matches[1];
                    $type = $matches[2];
                    if (empty($value)) {
                        $value = array();
                    } else {
                        $value = \explode(',', $value);
                        $value = \array_map('trim', $value);
                    }
                    if (!empty($type)) {
                        $value['__type'] = $type;
                    }
                }
                $current[$name] = $value;
            }
        }
        return $result;
    }

    /**
     * @param string $filename
     * @param string $error
     * @throw \go\I18n\Exceptions\FormatFile
     */
    private static function error($filename, $error)
    {
        throw new FormatFile('ui', $filename, $error);
    }
}
