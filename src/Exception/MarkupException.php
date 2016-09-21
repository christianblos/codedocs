<?php
namespace CodeDocs\Exception;

class MarkupException extends \Exception
{
    /**
     * @var null|string
     */
    private $path;

    /**
     * @var int
     */
    private $at;

    /**
     * @param string $message
     * @param string $path
     * @param int    $at
     */
    public function __construct($message, $path = null, $at = 0)
    {
        parent::__construct($message);
        $this->path = $path;
        $this->at   = $at;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getAt()
    {
        return $this->at;
    }
}
