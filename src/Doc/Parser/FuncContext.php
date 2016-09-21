<?php
namespace CodeDocs\Doc\Parser;

use CodeDocs\Doc\Lexer;
use CodeDocs\Exception\TokenException;

class FuncContext extends Context
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed[]
     */
    public $params = [];

    /**
     * @var string
     */
    private $currentLabel;

    /**
     * @param Context $parent
     * @param array   $token
     */
    public function __construct(array $token, Context $parent)
    {
        parent::__construct($token, $parent);
        $this->name = rtrim(rtrim(trim($token[Lexer::VALUE]), '('));
    }

    /**
     * @param array  $token
     * @param string $content
     *
     * @return Context|null
     * @throws TokenException
     */
    public function enterToken(array $token, $content)
    {
        switch ($token[Lexer::TYPE]) {
            case Lexer::T_FUNC_CLOSE:
                return $this->parent;

            case Lexer::T_LABEL:
                $this->currentLabel = trim(substr(trim($token[Lexer::VALUE]), 0, -1));
                break;

            case Lexer::T_QUOTED_STRING:
            case Lexer::T_NUMBER:
            case Lexer::T_TRUE:
            case Lexer::T_FALSE:
            case Lexer::T_NULL:
                $value = $this->getValueOfToken($token);
                $this->addParam($value, $token);
                break;

            case Lexer::T_ARRAY_OPEN:
                $array = new ArrayContext($token, $this);
                $this->addParam($array, $token);

                return $array;
                break;

            case Lexer::T_FUNC_OPEN:
                $func = new FuncContext($token, $this);
                $this->addParam($func, $token);

                return $func;

            case Lexer::T_COMMA:
                break;

            default:
                return null;
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @param array $token
     *
     * @throws TokenException
     */
    private function addParam($value, array $token)
    {
        if (!$this->currentLabel) {
            throw new TokenException(sprintf('parameter name missing in %s()', $this->name), $token);
        }

        $this->params[$this->currentLabel] = $value;

        $this->currentLabel = null;
    }
}
