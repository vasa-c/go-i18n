<?php
/**
 * Interface for multi-language containers
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface IMultiContainer
{
    /**
     * Get the key of this container
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the locale for this container
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language);

    /**
     * Get a sub-container (multi version)
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiSubcontainer($path);

    /**
     * Get a nested type (multi version)
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiType($path);

    /**
     * Check if the sub-container is exists
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
     * Magic get (local, multi subcontainer or multi type)
     *
     * @example $i18n->items->ru // local
     * @example $i18n->items->subcontainer
     * @example $i18n->items->subcontainer->type // type from subcontainer
     * @example $i18n->items->subcontainer->ru // local from subcontainer
     *
     * @param string $key
     * @return object
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function __get($key);

    /**
     * Magic isset (local, multi subcontainer or multi type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);
}
