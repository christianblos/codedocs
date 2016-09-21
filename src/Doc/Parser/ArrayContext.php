<?php
namespace CodeDocs\Doc\Parser;

use CodeDocs\Doc\Lexer;
use CodeDocs\Exception\TokenException;

class ArrayContext extends Context
{
    /**
     * @var mixed[]
     */
    public $values = [];

    /**
     * @var string|null
     */
    private $currentKey;

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
            case Lexer::T_ARRAY_CLOSE:
                return $this->parent;

            case Lexer::T_ARRAY_ARROW:
                $this->currentKey = array_pop($this->values);

                if (!is_scalar($this->currentKey)) {
                    throw new TokenException('array key must be scalar', $token);
                }
                break;

            case Lexer::T_QUOTED_STRING:
            case Lexer::T_NUMBER:
            case Lexer::T_TRUE:
            case Lexer::T_FALSE:
            case Lexer::T_NULL:
                $value = $this->getValueOfToken($token);
                $this->addValue($value);
                break;

            case Lexer::T_ARRAY_OPEN:
                $array = new ArrayContext($token, $this);
                $this->addValue($array);

                return $array;
                break;

            case Lexer::T_FUNC_OPEN:
                $func = new FuncContext($token, $this);
                $this->addValue($func);

                return $func;

            case Lexer::T_COMMA:
                if ($this->currentKey !== null) {
                    return null;
                }
                break;

            default:
                return null;
        }

        return $this;
    }

    /**
     * @param mixed $value
     */
    private function addValue($value)
    {
        if ($this->currentKey !== null) {
            $this->values[$this->currentKey] = $value;
        } else {
            $this->values[] = $value;
        }

        $this->currentKey = null;
    }
}
