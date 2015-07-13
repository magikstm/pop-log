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
namespace Pop\Log\Writer;

/**
 * Mail log writer class
 *
 * @category   Pop
 * @package    Pop_Log
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Mail implements WriterInterface
{

    /**
     * Array of emails in which to send the log messages
     * @var array
     */
    protected $emails = [];

    /**
     * Array of mail-specific options, i.e. subject, headers, etc.
     * @var array
     */
    protected $options = [];

    /**
     * Constructor
     *
     * Instantiate the Mail writer object
     *
     * @param  mixed $emails
     * @param  array $options
     * @throws Exception
     * @return Mail
     */
    public function __construct($emails, array $options = [])
    {
        $this->options = $options;

        if (!is_array($emails)) {
            $emails = [$emails];
        }

        foreach ($emails as $key => $value) {
            if (!is_numeric($key)) {
                $this->emails[] = [
                    'name'  => $key,
                    'email' => $value
                ];
            } else {
                $this->emails[] = [
                    'email' => $value
                ];
            }
        }
    }

    /**
     * Write to the log
     *
     * @param  array $logEntry
     * @return Mail
     */
    public function writeLog(array $logEntry)
    {
        $subject = (isset($this->options['subject'])) ?
            $this->options['subject'] :
            'Log Entry:';

        $subject .= ' ' . $logEntry['name'] . ' (' . $logEntry['priority'] . ')';

        $mail = new \Pop\Mail\Mail($subject, $this->emails);
        if (isset($this->options['headers'])) {
            $mail->setHeaders($this->options['headers']);
        }

        $entry = implode("\t", $logEntry) . PHP_EOL;

        $mail->setText($entry)
             ->send();

        return $this;
    }

}
