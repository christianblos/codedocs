<?php
namespace CodeDocs\Doc\Parser;

use CodeDocs\Doc\Lexer;

abstract class Context
{
    /**
     * @var int
     */
    public $at;

    /**
     * @var Context|null
     */
    protected $parent;

    /**
     * @param array|null   $token
     * @param Context|null $parent
     */
    public function __construct(array $token = null, Context $parent = null)
    {
        $this->at     = $token === null ? 0 : $token[Lexer::AT];
        $this->parent = $parent;
    }

    /**
     * @param array  $token
     * @param string $content
     *
     * @return Context|null
     */
    abstract public function enterToken(array $token, $content);

    /**
     * @param array $token
     *
     * @return mixed
     */
    protected function getValueOfToken(array $token)
    {
        switch ($token[Lexer::TYPE]) {
            case Lexer::T_QUOTED_STRING:
                return substr($token[Lexer::VALUE], 1, -1);

            case Lexer::T_NUMBER:
                return (float)$token[Lexer::VALUE];

            case Lexer::T_TRUE:
                return true;

            case Lexer::T_FALSE:
                return false;

            case Lexer::T_NULL:
                return null;
        }

        return null;
    }
}
