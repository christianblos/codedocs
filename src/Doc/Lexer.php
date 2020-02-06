<?php
namespace CodeDocs\Doc;

use Exception;

class Lexer
{
    const AT     = 'at';
    const TYPE   = 'type';
    const VALUE  = 'val';
    const LENGTH = 'length';

    const T_MARKUP_OPEN_ESCAPED = 'MARKUP_OPEN_QUOTED';
    const T_MARKUP_OPEN         = 'MARKUP_OPEN';
    const T_MARKUP_CLOSE        = 'MARKUP_CLOSE';
    const T_FUNC_OPEN           = 'FUNC_OPEN';
    const T_FUNC_CLOSE          = 'FUNC_CLOSE';
    const T_LABEL               = 'LABEL';
    const T_QUOTED_STRING       = 'QUOTED_STRING';
    const T_NUMBER              = 'NUMBER';
    const T_TRUE                = 'TRUE';
    const T_FALSE               = 'FALSE';
    const T_NULL                = 'NULL';
    const T_ARRAY_OPEN          = 'ARRAY_OPEN';
    const T_ARRAY_CLOSE         = 'ARRAY_CLOSE';
    const T_ARRAY_ARROW         = 'ARRAY_ARROW';
    const T_COMMA               = 'COMMA';
    const T_ANY                 = 'ANY';

    /**
     * @var string
     */
    private $input;

    /**
     * @var array
     */
    private $patterns = [];

    /**
     * @var string
     */
    private $currentInput;

    /**
     * @var int
     */
    private $currentPos = 0;

    /**
     * @var array|null
     */
    private $currentToken;

    /**
     *
     */
    public function __construct()
    {
        $this->patterns = [
            self::T_MARKUP_OPEN_ESCAPED => '\\\\\{\{',
            self::T_MARKUP_OPEN         => '\{\{\s*',
            self::T_MARKUP_CLOSE        => '\s*\}\}',

            self::T_FUNC_OPEN  => '[a-zA-Z0-9_\-\\\\\/]+\s*\(\s*',
            self::T_FUNC_CLOSE => '\s*\)\s*',
            self::T_LABEL      => '[a-zA-Z0-9_]+\s*:\s*',

            self::T_QUOTED_STRING => '("(([^"\\\\])*(\\\\"|\\\\[^"])*)*"|\'(([^\'\\\\])*(\\\\\'|\\\\[^\'])*)*\')',
            self::T_NUMBER        => '[0-9\.]+',
            self::T_TRUE          => 'true',
            self::T_FALSE         => 'false',
            self::T_NULL          => 'null',

            self::T_ARRAY_OPEN  => '\[\s*',
            self::T_ARRAY_CLOSE => '\s*\]',
            self::T_ARRAY_ARROW => '\s*=>\s*',

            self::T_COMMA => '\s*,\s*',
        ];
    }

    /**
     * @param string $input
     *
     * @return void
     */
    public function setInput($input)
    {
        $this->input        = $input;
        $this->currentInput = $input;
        $this->currentPos   = 0;
        $this->currentToken = null;
    }

    /**
     * Get next token
     *
     * @return array
     * @throws Exception
     */
    public function getNext()
    {
        if ($this->currentToken !== null || strlen($this->currentInput) > 0) {
            $this->parseNextToken();

            return $this->currentToken;
        }

        return null;
    }

    /**
     * @throws Exception
     */
    private function parseNextToken()
    {
        if ($this->currentInput) {
            $typeInfo = $this->scanNextType($this->currentInput);

            if ($typeInfo === null) {
                $typeInfo = $this->getAnyType($this->currentInput);
            }

            $type   = $typeInfo['type'];
            $match  = $typeInfo['match'];
            $length = $typeInfo['length'];

            $this->currentToken = [
                self::AT     => $this->currentPos,
                self::TYPE   => $type,
                self::VALUE  => $match,
                self::LENGTH => $length,
            ];

            $this->currentInput = substr($this->currentInput, $length);
            $this->currentPos += $length;
        } else {
            $this->currentToken = null;
        }
    }

    /**
     * Determines the next type of the given string
     *
     * @param string $string
     *
     * @return array|null
     */
    private function scanNextType($string)
    {
        foreach ($this->patterns as $type => $pattern) {
            if (preg_match('/^(' . $pattern . ')/sS', $string, $matches) === 1) {
                return [
                    'type'   => $type,
                    'match'  => $matches[1],
                    'length' => strlen($matches[1]),
                ];
            }
        }

        return null;
    }

    /**
     * Get next ANY-type which is a string that does not match to any token
     *
     * @param string $string
     *
     * @return array|null
     */
    private function getAnyType($string)
    {
        $anyString = '';

        do {
            $anyString .= $string[0];
            $string = substr($string, 1);

            $typeInfo = $this->scanNextType($string);

            // ignore all until markup reached
            if ($typeInfo != null && $typeInfo['type'] !== self::T_MARKUP_OPEN && $typeInfo['type'] !== self::T_MARKUP_OPEN_ESCAPED) {
                $typeInfo = null;
            }
        } while ($typeInfo === null && $string);

        return [
            'type'   => self::T_ANY,
            'match'  => $anyString,
            'length' => strlen($anyString),
        ];
    }
}
