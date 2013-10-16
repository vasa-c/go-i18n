<?php
/**
 * The helper for url-service
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

class Urls
{
    /**
     * Normalize the config of urls
     *
     * @param array $urls
     * @return array
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public static function normalizeUrlsConfig($urls = null)
    {
        if (!\is_array($urls)) {
            if (($urls === null) || ($urls === true)) {
                return array();
            }
            if ($urls === false) {
                return array('' => false);
            }
            throw new \go\I18n\Exceptions\ConfigInvalid('Urls.urls');
        }
        \end($urls);
        if ((\key($urls) == '') && (\current($urls))) {
            unset($urls['']);
        }
        return $urls;
    }
}
