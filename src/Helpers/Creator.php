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
     * @param mixed $pointer
     *        parameters of instance
     * @param array $options [optional]
     *        "default" - the default class name
     *        "base" - the base class for check
     *        "key" - the config key for debug
     *        "args" - arguments for constructor (precede params)
     * @return object
     *         the target instance
     * @throws \go\I18n\Exceptions\ConfigService
     */
    public static function create($pointer, array $options = array())
    {
        if (\is_object($pointer)) {
            $instance = $pointer;
        } else {
            if (\is_array($pointer)) {
                $classname = isset($pointer['classname']) ? $pointer['classname'] : null;
                $params = $pointer;
            } elseif (\is_string($pointer)) {
                $classname = $pointer;
                $params = array();
            } elseif (\is_null($pointer) || ($pointer === true)) {
                $classname = null;
                $params = array();
            } elseif ($pointer === false) {
                $key = isset($options['key']) ? $options['key'] : '';
                throw new ServiceDisabled($key);
            } else {
                $key = isset($options['key']) ? $options['key'] : '';
                throw new ConfigService($key, 'Type is not valid');
            }
            if ($classname) {
                if ((!empty($options['ns'])) && ($classname[0] !== '\\')) {
                    $classname = $options['ns'].'\\'.$classname;
                }
            } else {
                if (empty($options['default'])) {
                    $key = isset($options['key']) ? $options['key'] : '';
                    throw new ConfigService($key, 'Classname is not specified');
                }
                $classname = $options['default'];
            }
            if (!\class_exists($classname)) {
                $key = isset($options['key']) ? $options['key'] : '';
                throw new ConfigService($key, 'Class '.$classname.' is undefined');
            }
            if (empty($options['args'])) {
                $instance = new $classname($params);
            } else {
                $args = $options['args'];
                $args[] = $params;
                $class = new \ReflectionClass($classname);
                $instance = $class->newInstanceArgs($args);
            }
        }
        if ((!empty($options['base'])) && (!($instance instanceof $options['base']))) {
            $key = isset($options['key']) ? $options['key'] : '';
            throw new ConfigService($key, 'Must be an instance of '.$options['base']);
        }
        return $instance;
    }
}
