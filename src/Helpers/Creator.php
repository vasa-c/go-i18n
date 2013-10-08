<?php
/**
 * Create a instance by parameters
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\ConfigService;
use go\I18n\Exceptions\ServiceDisabled;

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
     * @param mixed $addarg [optional]
     *        the additional argument for constructor
     * @return object
     *         the target instance
     * @throws \go\I18n\Exceptions\ConfigService
     */
    public static function create($params, $default = null, $base = null, $key = null, $addarg = null)
    {
        if (\is_object($params)) {
            $instance = $params;
        } else {
            if (\is_array($params)) {
                $classname = isset($params['classname']) ? $params['classname'] : null;
                $arg = $params;
            } elseif (\is_string($params)) {
                $classname = $params;
                $arg = array();
            } elseif (\is_null($params) || ($params === true)) {
                $classname = null;
                $arg = array();
            } elseif ($params === false) {
                throw new ServiceDisabled($key);
            } else {
                throw new ConfigService($key, 'Type is not valid');
            }
            if (!$classname) {
                if (!$default) {
                    throw new ConfigService($key, 'Classname is not specified');
                }
                $classname = $default;
            }
            if (!\class_exists($classname)) {
                throw new ConfigService($key, 'Class '.$classname.' is undefined');
            }
            if ($addarg !== null) {
                $instance = new $classname($arg, $addarg);
            } else {
                $instance = new $classname($arg);
            }
        }
        if (($base) && (!($instance instanceof $base))) {
            throw new ConfigService($key, 'Must be an instance of '.$base);
        }
        return $instance;
    }
}
