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

    /**
     * Search an url in the config and define a version (multi/single)
     *
     * @param array $config
     * @param string $url
     * @return boolean
     */
    public static function defineVersion($config, $url)
    {
        foreach ($config as $prefix => $version) {
            if (empty($prefix)) {
                return $version;
            }
            if (\strpos($url, $prefix) === 0) {
                return $version;
            }
        }
        return true;
    }
}
