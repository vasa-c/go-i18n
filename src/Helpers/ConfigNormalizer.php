<?php
/**
 * The normalizer configuration
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

class ConfigNormalizer
{
    /**
     * Normalize the list of languages
     *
     * @param array $languages
     *        the list from config
     * @param string $default [optional]
     *        the default language
     * @return array
     *         the languages list in normal form
     */
    public static function languagesNormalize(array $languages, $default = null)
    {
        foreach ($languages as $key => &$params) {
            if (\is_array($params)) {
                if (!isset($params['title'])) {
                    $params['title'] = $key;
                }
                if (!\array_key_exists('parent', $params)) {
                    $params['parent'] = ($key === $default) ? null : $default;
                }
                if (!isset($params['url'])) {
                    $params['url'] = $key;
                }
            } else {
                if (\is_string($params)) {
                    $parent = $params;
                } elseif ($key !== $default) {
                    $parent = $default;
                } else {
                    $parent = null;
                }
                $params = array(
                    'title' => $key,
                    'parent' => $parent,
                    'url' => $key,
                );
            }
        }
        return $languages;
    }
}
