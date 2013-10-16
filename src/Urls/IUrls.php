<?php
/**
 * The interface of i18n->urls service
 *
 * Result of resolve:
 * "language" - the determined language version (null - see redirect)
 * "multi" - use the multi languages mode (boolean)
 * "redirect" - the url to redirect
 * "rel_url" - the relative url in this language version
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n;

interface IUrls
{
    /**
     * Resolve url (determine language version)
     *
     * @param array $params [optional]
     *        the list of parameters for resolove (implementation depended)
     * @return array
     *         the result of resolve
     */
    public function resolve(array $params = null);

    /**
     * Get the result of resolve
     *
     * @return array
     */
    public function getResolveResult();

    /**
     * Create the url of resource
     *
     * @param string $relUrl
     *        a relative url (in the current language version)
     * @param array|string $data [optional]
     *        data for GET
     * @param string $language [optional]
     *        a language version (current by default)
     */
    public function url($relUrl, $data = null, $language = null);
}
