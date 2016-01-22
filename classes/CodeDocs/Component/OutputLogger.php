<?php
namespace CodeDocs\Component;

class OutputLogger
{
    /**
     * @var int
     */
    private $logLevel;

    /**
     * @param int $logLevel
     */
    public function __construct($logLevel = 0)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * @param int    $level
     * @param string $message
     */
    public function log($level, $message)
    {
        if ($level > $this->logLevel) {
            return;
        }

        if ($level > 0) {
            echo str_repeat('   ', $level - 1);
            echo ' > ';
        }

        echo $message . PHP_EOL;
    }
}
