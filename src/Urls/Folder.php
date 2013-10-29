<?php
/**
 * The implementation of IUrls interface (the language as a first folder)
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Urls;

use go\I18n\Helpers\Urls;
use go\I18n\Exceptions\UrlsAlreadyResolved;
use go\I18n\Exceptions\UrlsNotResolved;

class Folder implements IUrls
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param array $config
     */
    public function __construct(\go\I18n\Helpers\Context $context, array $config)
    {
        $this->context = $context;
        $this->config = $config;
        $this->curls = Urls::normalizeUrlsConfig(isset($config['urls']) ? $config['urls'] : null);
    }

    /**
     * @override \go\I18n\Urls\IUrls
     *
     * @param array $params [optional]
     *        url parameters ($_SERVER by default)
     * @param boolean $useres
     * @return \go\I18n\Urls\Result
     * @throws \go\I18n\Exceptions\UrlsAlreadyResolverd
     * @throws \go\I18n\Exceptions\CurrentAlreadySpecified
     */
    public function resolve(array $params = null, $useres = true)
    {
        if ($this->result) {
            throw new UrlsAlreadyResolved();
        }
        $this->loadParams($params);
        $this->result = array(
            'language' => $this->context->default,
            'multi' => false,
            'redirect' => null,
            'rel_url' => null,
        );
        if (!$this->isCLI()) {
            $this->doResolve();
        }
        if ($useres) {
            $this->context->i18n->setCurrentLanguage($this->result['language']);
        }
        return $this->getResolveResult();
    }

    /**
     * @override \go\I18n\Urls\IUrls
     *
     * @return \go\I18n\Urls\Result
     */
    public function getResolveResult()
    {
        if (!$this->oresult) {
            if (!$this->result) {
                return null;
            }
            $this->oresult = new Result($this->result);
        }
        return $this->oresult;
    }

    /**
     * @override \go\I18n\Urls\IUrls
     *
     * @param string $relUrl
     * @param array|string $data [optional]
     * @param boolean $absolute [optional]
     * @param string $language [optional]
     * @throws \go\I18n\Exceptions\UrlsNotResolverd
     */
    public function url($relUrl, $data = null, $absolute = false, $language = null)
    {
        if (!$language) {
            if (!$this->result) {
                throw new UrlsNotResolved();
            }
            $language = $this->result['language'];
        }
        if (\substr($relUrl, 0, 1) !== '/') {
            $multi = Urls::defineVersion($this->curls, $relUrl);
            if ($multi) {
                $url = '/'.$language.'/'.$relUrl;
            } else {
                $url = '/'.$relUrl;
            }
        } else {
            $url = $relUrl;
        }
        if (!empty($data)) {
            if (\is_array($data)) {
                $data = \http_build_query($data);
            }
            if (\strpos($relUrl, '?') === false) {
                $sep = '?';
            } else {
                $sep = '&';
            }
            $url .= $sep.$data;
        }
        if ($absolute) {
            $url = $this->createAbsoluteUrl($url);
        }
        return $url;
    }

    /**
     * Load a list of resolve parameters
     *
     * @param array $params
     */
    private function loadParams($params)
    {
        if (!\is_array($params)) {
            $params = $_SERVER;
        }
        foreach ($this->params as $k => $v) {
            if (\array_key_exists($k, $params)) {
                $this->params[$k] = $params[$k];
            }
        }
        if (empty($this->params['PHP_SAPI'])) {
            $this->params['PHP_SAPI'] = \PHP_SAPI;
        }
    }

    /**
     * Resolve process
     */
    private function doResolve()
    {
        $uri = $this->params['REQUEST_URI'];
        $uri = \explode('?', $uri, 2);
        $data = isset($uri[1]) ? '?'.$uri[1] : '';
        $doc = $uri[0];
        $doc = \explode('/', $doc, 3);
        $language = isset($doc[1]) ? $doc[1] : null;
        $rel = (isset($doc[2]) ? $doc[2] : '').$data;
        if (isset($this->context->languages[$language])) {
            $this->doResolveInside($language, $rel);
        } else {
            if ($language) {
                $rel = $language.'/'.$rel;
            }
            $this->doResolveOutside($rel);
        }
    }

    /**
     * Resolve inside a language version
     *
     * @param string $language
     * @param string $rel
     */
    private function doResolveInside($language, $rel)
    {
        $multi = Urls::defineVersion($this->curls, $rel);
        if ($multi) {
            $this->result['language'] = $language;
            $this->result['multi'] = true;
            $this->result['rel_url'] = $rel;
        } else {
            $this->result['redirect'] = $this->createAbsoluteUrl('/'.$rel);
            $this->result['rel_url'] = $rel;
        }
    }

    /**
     * Resolve outside a language version
     *
     * @param string $rel
     */
    private function doResolveOutside($rel)
    {
        $multi = Urls::defineVersion($this->curls, $rel);
        if ($multi) {
            $language = $this->defineUserLanguage();
            if ($language) {
                $this->context->mustLanguageExists($language);
                $this->result['language'] = $language;
            }
            $url = '/'.$this->result['language'].'/'.$rel;
            $this->result['multi'] = true;
            $this->result['redirect'] = $this->createAbsoluteUrl($url);
            $this->result['rel_url'] = $rel;
        } else {
            $this->result['rel_url'] = $rel;
        }
    }

    /**
     * Create an absolute url
     *
     * @param string $full
     *        a full url ("/" - first)
     * @return string
     */
    private function createAbsoluteUrl($full)
    {
        $comp = array();
        $comp[] = $this->params['HTTPS'] ? 'https://' : 'http://';
        $comp[] = $this->params['HTTP_HOST'];
        $comp[] = $full;
        return \implode('', $comp);
    }

    /**
     * Try define an user language
     *
     * @return string|null
     */
    private function defineUserLanguage()
    {
        if (isset($this->config['user_def'])) {
            return \call_user_func($this->config['user_def']);
        }
        return null;
    }

    /**
     * @return boolean
     */
    private function isCLI()
    {
        if (\is_bool($this->params['IS_CLI'])) {
            return $this->params['IS_CLI'];
        } else {
            return ($this->params['PHP_SAPI'] === 'cli');
        }
    }

    /**
     * @var array
     */
    private $params = array(
        'REQUEST_URI' => '',
        'PHP_SAPI' => '',
        'HTTP_HOST' => '',
        'HTTPS' => false,
        'IS_CLI' => null,
    );

    /**
     * @var \go\I18n\Helpers\Context
     */
    private $context;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $curls;

    /**
     * @var array
     */
    private $result;

    /**
     * @var \go\I18n\Urls\Result
     */
    private $oresult;
}
