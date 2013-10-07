<?php
/**
 * The interface of an UI-node
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

interface INode extends \ArrayAccess
{
    /**
     * Get a key of the node
     *
     * @example $i18n->ui->ru->one->two->getKey(); // "one.two"
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the main i18n object
     *
     * @return \go\I18n\I18n
     */
    public function getI18n();

    /**
     * Get a value by the path
     *
     * @param string|array $path
     * @return \go\I18n\UI\INode
     * @throws \go\I18n\Exceptions\UIKeyNotFound
     */
    public function get($path);

    /**
     * Check if value exists
     *
     * @param string|array $path
     * @return boolean
     */
    public function exists($path);

    /**
     * Magic get
     *
     * @param string $key
     * @return \go\I18n\UI\INode
     * @throws \go\I18n\Exceptions\UIKeyNotFound
     */
    public function __get($key);

    /**
     * Magic isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);
}
