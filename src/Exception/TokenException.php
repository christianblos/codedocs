<?php
namespace CodeDocs\Exception;

class TokenException extends \Exception
{
    /**
     * @var array
     */
    private $token;

    /**
     * @param string $message
     * @param array  $token
     */
    public function __construct($message, array $token)
    {
        parent::__construct($message);
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }
}
