<?php
/**
 * Error in I/O function
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class IOError extends Logic implements IO
{
    /**
     * Constructor
     *
     * @param string $filename [optional]
     * @param string $ioerror [optional]
     */
    public function __construct($filename = null, $ioerror = null)
    {
        $this->filename = $filename;
        $this->ioerror = $ioerror;
        $message = 'I\O error';
        if ($ioerror) {
            $message .= '. '.$ioerror;
        }
        if ($filename) {
            $message .= '. File: '.$filename;
        }
        parent::__construct($message);
    }

    /**
     * @var string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @var string
     */
    public function getIOError()
    {
        return $this->ioerror;
    }

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $ioerror;
}
