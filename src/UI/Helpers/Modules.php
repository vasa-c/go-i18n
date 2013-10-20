<?php
/**
 * The list of modules for the RootModules class
 */

namespace go\I18n\UI\Helpers;

use go\I18n\Exceptions\ConfigInvalid;

class Modules
{
    /**
     * Constructor
     *
     * @param array $params
     *        the parameters list of a RootModule
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Is module exists
     *
     * @param string $module
     * @return boolean
     * @throw \go\I18n\Exceptions\ConfigInvalid
     */
    public function exists($module)
    {
        $modules = $this->getModules();
        return isset($this->modules[$module]);
    }

    /**
     * Get the dir for a module
     *
     * @param string $module
     * @return string
     * @throw \go\I18n\Exceptions\ConfigInvalid
     */
    public function getDir($module)
    {
        if (!$this->exists($module)) {
            return null;
        }
        if (!\is_string($this->modules[$module])) {
            if (!$this->creator) {
                if (isset($this->params['pattern_dir'])) {
                    $this->pattern = $this->params['pattern_dir'];
                    $this->creator = array($this, 'patternDir');
                } elseif (isset($this->params['get_dir'])) {
                    $this->creator = $this->params['get_dir'];
                } else {
                    throw new ConfigInvalid('UI: pattern_dir is not found');
                }
            }
            $this->modules[$module] = \call_user_func($this->creator, $module);
        }
        return $this->modules[$module];
    }

    /**
     * Get the list of modules
     *
     * @return array
     * @throw \go\I18n\Exceptions\ConfigInvalid
     */
    public function getListModules()
    {
        return \array_keys($this->getModules());
    }

    /**
     * @return array
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    private function getModules()
    {
        if (!$this->modules) {
            if (isset($this->params['modules'])) {
                $this->modules = $this->params['modules'];
            } elseif (isset($this->params['get_modules'])) {
                $this->modules = \call_user_func($this->params['get_modules']);
            } else {
                throw new ConfigInvalid('UI: modules are not found');
            }
        }
        return $this->modules;
    }

    /**
     * Create the directory name by a pattern
     *
     * @param string $dir
     * @return string
     */
    private function patternDir($dir)
    {
        return \str_replace('{{ module }}', $dir, $this->pattern);
    }

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $modules;

    /**
     * @var callable
     */
    private $creator;

    /**
     * @var string
     */
    private $pattern;
}
