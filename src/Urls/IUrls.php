<?php
/**
 * The interface of i18n->urls service
 *
 * Result of resolve: see Result-class
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Urls;

interface IUrls
{
    /**
     * Resolve url (determine language version)
     *
     * @param array $params [optional]
     *        the list of parameters for resolove (implementation depended)
     * @param boolean $useres
     *        use results for set parameters of the i18n object
     * @return \go\I18n\Urls\Result
     *         the result of resolve
     * @throws \go\I18n\Exceptions\UrlsAlreadyResolverd
     * @throws \go\I18n\Exceptions\CurrentAlreadySpecified
     */
    public function resolve(array $params = null, $useres = true);

    /**
     * Get the result of resolve
     *
     * @return \go\I18n\Urls\Result
     *         result or NULL if is not resolved
     */
    public function getResolveResult();

    /**
     * Create the url of resource
     *
     * @param string $relUrl
     *        a relative url (in the current language version)
     * @param array|string $data [optional]
     *        data for GET
     * @param boolean $absolute [optional]
     *        create an absolute URI
     * @param string $language [optional]
     *        a language version (current by default)
     * @throws \go\I18n\Exceptions\UrlsNotResolverd
     */
    public function url($relUrl, $data = null, $absolute = false, $language = null);
}
