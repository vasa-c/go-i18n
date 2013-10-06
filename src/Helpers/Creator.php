<?php
/**
 * Create a instance by parameters
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\ConfigInvalid;

class Creator
{
    /**
     * Create a instance by parameters
     *
     * @param mixed $params
     *        parameters of instance
     * @param string $default [optional]
     *        the default class name
     * @param string $base [optional]
     *        the base class for check
     * @param string $key [optional]
     *        the config key for debug
     * @return object
     *         the target instance
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public static function create($params, $default = null, $base = null, $key = null)
    {
        if (\is_object($params)) {
            $instance = $params;
        } else {
            if (\is_array($params)) {
                $classname = isset($params['classname']) ? $params['classname'] : null;
                $arg = $params;
            } else {
                $classname = $params;
                $arg = array();
            }
            if (!$classname) {
                if (!$default) {
                    throw new ConfigInvalid($key.': classname is not specified');
                }
                $classname = $default;
            }
            if (!\class_exists($classname)) {
                throw new ConfigInvalid($key.' is an instance of undefined class');
            }
            $instance = new $classname($arg);
        }
        if (($base) && (!($instance instanceof $base))) {
            throw new ConfigInvalid($key.' must be an instance of '.$base);
        }
        return $instance;
    }
}
