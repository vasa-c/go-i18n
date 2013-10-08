<?php
/**
 * The list of UI adapters
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

class Adapters
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param array $cadapters
     *        the adapters list from config
     */
    public static function createUIAdapters(\go\I18n\Helpers\Context $context, $cadapters)
    {
        $instance = new self($context, $cadapters);
        $context->adaptersUI = $instance;
        return $instance;
    }

    /**
     * The default list of adapters
     *
     * @var array
     */
    private static $defaultAdapters = array(
        'files' => array(
            'php' => '\go\I18n\UI\Php',
            'ui' => '\go\I18n\UI\Ui',
            'txt' => '',
        ),
        'dir' => '\go\I18n\UI\Dir',
        'inline_dir' => true,
        'data' => '\go\I18n\UI\Data',
        'empty' => '\go\I18n\UI\EmptyData',
    );

    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param array $cadapters
     */
    private function __construct(\go\I18n\Helpers\Context $context, $cadapters)
    {
        $this->context = $context;
        $adapters = self::$defaultAdapters;
        if (\is_array($cadapters)) {
            $adapters = \array_replace_recursive($adapters, $cadapters);
        }
        if ($adapters['inline_dir'] === true) {
            $adapters['inline_dir'] = $adapters['dir'];
        }
        $this->adapters = $adapters;
    }

    /**
     * Find and create a node by the key
     *
     * @param string $language
     * @param string $dirname
     * @param string $key
     * @param string $fkey [optional]
     * @return \go\I18n\UI\INode
     */
    public function createNode($language, $dirname, $key, $fkey = null)
    {
        if ($fkey === null) {
            $fkey = $key;
        }
        $io = $this->context->getIO();
        $base = $dirname.'/'.$key;
        foreach ($this->adapters['files'] as $ext => $classname) {
            if ($classname === null) {
                continue;
            }
            $filename = $base.'.'.$ext;
            if ($io->isFile($filename)) {
                if ($classname === '') {
                    return $io->getContents($filename);
                }
                $inline = null;
                $cninline = $this->adapters['inline_dir'];
                if ($cninline) {
                    if ($io->isDir($base)) {
                        $inline = new $cninline($this->context, $fkey, $language, $base);
                    }
                }
                return new $classname($this->context, $fkey, $language, $filename, $inline);
            }
        }
        $classname = $this->adapters['dir'];
        if ($classname) {
            if ($io->isDir($base)) {
                return new $classname($this->context, $fkey, $language, $base);
            }
        }
        return null;
    }

    /**
     * Create the data-node
     *
     * @param string $language
     * @param string $fkey
     * @param array $data
     * @return \go\I18n\UI\INode
     */
    public function createDataNode($language, $fkey, array $data)
    {
        $classname = $this->adapters['data'];
        return new $classname($this->context, $fkey, $language, $data);
    }

    /**
     * Create the empty data-node
     *
     * @param string $language
     * @param string $fkey
     * @return \go\I18n\UI\INode
     */
    public function createEmptyDataNode($language, $fkey)
    {
        $classname = $this->adapters['empty'];
        return new $classname($this->context, $fkey, $language);
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    private $context;

    /**
     * @var array
     */
    private $adapters;
}
