<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Log;

/**
 * Logger class
 *
 * @category   Pop
 * @package    Pop_Log
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Logger
{

    /**
     * Constants for message priorities
     * @var int
     */
    const EMERG  = 0;
    const ALERT  = 1;
    const CRIT   = 2;
    const ERR    = 3;
    const WARN   = 4;
    const NOTICE = 5;
    const INFO   = 6;
    const DEBUG  = 7;

    /**
     * Message priority short codes
     * @var array
     */
    protected $priorities = [
        0 => 'EMERG',
        1 => 'ALERT',
        2 => 'CRIT',
        3 => 'ERR',
        4 => 'WARN',
        5 => 'NOTICE',
        6 => 'INFO',
        7 => 'DEBUG',
    ];

    /**
     * Log writers
     * @var array
     */
    protected $writers = [];

    /**
     * Log timestamp format
     * @var string
     */
    protected $timestamp = 'Y-m-d H:i:s';

    /**
     * Constructor
     *
     * Instantiate the logger object
     *
     * @param  Writer\WriterInterface $writer
     * @return Logger
     */
    public function __construct(Writer\WriterInterface $writer = null)
    {
        if (null !== $writer) {
            $this->addWriter($writer);
        }
    }

    /**
     * Add a log writer
     *
     * @param  Writer\WriterInterface $writer
     * @return Logger
     */
    public function addWriter(Writer\WriterInterface $writer)
    {
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * Get all log writers
     *
     * @return array
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * Set timestamp format
     *
     * @param  string $format
     * @return Logger
     */
    public function setTimestamp($format = 'Y-m-d H:i:s')
    {
        $this->timestamp = $format;
        return $this;
    }

    /**
     * Get timestamp format
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Add a log entry
     *
     * @param  int   $priority
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function log($priority, $message, array $options = [])
    {
        $logEntry = [
            'timestamp' => date($this->timestamp),
            'priority'  => (int) $priority,
            'name'      => $this->priorities[$priority],
            'message'   => (string) $message
        ];

        foreach ($this->writers as $writer) {
            $writer->writeLog($logEntry, $options);
        }

        return $this;
    }

    /**
     * Add an EMERG log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function emerg($message, array $options = [])
    {
        return $this->log(self::EMERG, $message, $options);
    }

    /**
     * Add an ALERT log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function alert($message, array $options = [])
    {
        return $this->log(self::ALERT, $message, $options);
    }

    /**
     * Add a CRIT log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function crit($message, array $options = [])
    {
        return $this->log(self::CRIT, $message, $options);
    }

    /**
     * Add an ERR log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function err($message, array $options = [])
    {
        return $this->log(self::ERR, $message, $options);
    }

    /**
     * Add a WARN log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function warn($message, array $options = [])
    {
        return $this->log(self::WARN, $message, $options);
    }

    /**
     * Add a NOTICE log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function notice($message, array $options = [])
    {
        return $this->log(self::NOTICE, $message, $options);
    }

    /**
     * Add an INFO log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function info($message, array $options = [])
    {
        return $this->log(self::INFO, $message, $options);
    }

    /**
     * Add a DEBUG log entry
     *
     * @param  mixed $message
     * @param  array $options
     * @return Logger
     */
    public function debug($message, array $options = [])
    {
        return $this->log(self::DEBUG, $message, $options);
    }

}
