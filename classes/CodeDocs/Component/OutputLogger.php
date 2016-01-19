<?php
namespace CodeDocs\Component;

use Psr\Log\LoggerInterface;

class OutputLogger implements LoggerInterface
{
    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @param bool $isDebug
     */
    public function __construct($isDebug = false)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function emergency($message, array $context = [])
    {
        echo '[ERROR] ' . $message . PHP_EOL;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function alert($message, array $context = [])
    {
        echo '[ERROR] ' . $message . PHP_EOL;
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function critical($message, array $context = [])
    {
        echo '[ERROR] ' . $message . PHP_EOL;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function error($message, array $context = [])
    {
        echo '[ERROR] ' . $message . PHP_EOL;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function warning($message, array $context = [])
    {
        echo '[WARNING] ' . $message . PHP_EOL;
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function notice($message, array $context = [])
    {
        echo $message . PHP_EOL;
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function info($message, array $context = [])
    {
        echo $message . PHP_EOL;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function debug($message, array $context = [])
    {
        if ($this->isDebug === true) {
            echo $message . PHP_EOL;
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        echo $message . PHP_EOL;
    }
}
