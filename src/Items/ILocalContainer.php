<?php
/**
 * Interface for single-language containers
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface ILocalContainer
{
    /**
     * Get the key of this container
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the language of this container
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Get the multi-language version of this container
     *
     * @return \go\I18n\Items\IMultiContainer
     */
    public function getMulti();

    /**
     * Get the subcontainer (local)
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getSubcontainer($path);

    /**
     * Get the nested type (local)
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getType($path);

    /**
     * Check if the subcontainer container is exists
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path);

    /**
     * Check if the type is exists
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path);

    /**
     * Magic get (subcontainer or nested type)
     *
     * @example $i18n->items->ru->subcontainer (local subcontainer)
     * @example $i18n->items->ru->subcontainer->type (local type)
     *
     * @param string $key
     * @return object
     */
    public function __get($key);

    /**
     * Magic isset (subcontainer or nested type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);
}
