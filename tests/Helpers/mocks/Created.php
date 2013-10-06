<?php
/**
 * The mock for test of the creator helper
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers\mocks;

class Created implements ICreated
{
    /**
     * @param array $params
     */
    public function __construct(array $params = null)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @var array
     */
    private $params;
}
