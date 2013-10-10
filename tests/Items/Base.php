<?php

namespace go\Tests\I18n\Items;

abstract class Base extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testConfig;

    /**
     * @return array
     */
    protected function getTestConfig()
    {
        if (!$this->testConfig) {
            $this->testConfig = include(__DIR__.'/testconfig.php');
        }
        return $this->testConfig;
    }

    /**
     * @param array $itemsConfig [optional]
     * @return \go\I18n\Items\IMultiContainer
     */
    protected function create($itemsConfig = null)
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'items' => $itemsConfig ?: $this->getTestConfig(),
        );
        $i18n = new \go\I18n\I18n($config);
        return $i18n->items;
    }
}
