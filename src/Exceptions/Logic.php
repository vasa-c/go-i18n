<?php
/**
 * The basic class of library logic-exceptions
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

abstract class Logic extends \LogicException implements Exception
{
    /**
     * Constructor
     *
     * @param string $message
     */
    public function __construct($message = null)
    {
        $message = $message ?: $this->errorMessage;
        parent::__construct($message);
    }

    /**
     * Error message by default
     *
     * @var string
     */
    protected $errorMessage = 'I18n logic exception';
}
