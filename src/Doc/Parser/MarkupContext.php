<?php
namespace CodeDocs\Doc\Parser;

use CodeDocs\Doc\Lexer;

class MarkupContext extends Context
{
    /**
     * @var int
     */
    public $start;

    /**
     * @var int
     */
    public $end;

    /**
     * @var FuncContext|null
     */
    public $func;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $replacement;

    /**
     * @param array  $token
     * @param string $content
     *
     * @return Context|null
     */
    public function enterToken(array $token, $content)
    {
        switch ($token[Lexer::TYPE]) {
            case Lexer::T_MARKUP_CLOSE:
                $this->end  = $token[Lexer::AT] + $token[Lexer::LENGTH];
                $this->text = substr($content, $this->start, $this->end - $this->start);

                return $this->parent;

            case Lexer::T_FUNC_OPEN:
                if ($this->func !== null) {
                    return null;
                }
                $this->func = new FuncContext($token, $this);

                return $this->func;
        }

        return null;
    }
}
