<?php
namespace CodeDocs\Doc\Parser;

use CodeDocs\Doc\Lexer;

class FileContext extends Context
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var MarkupContext[]
     */
    public $markups = [];

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        parent::__construct();
    }

    /**
     * @param array  $token
     * @param string $content
     *
     * @return Context|null
     */
    public function enterToken(array $token, $content)
    {
        switch ($token[Lexer::TYPE]) {
            case Lexer::T_MARKUP_OPEN_ESCAPED:
                $context              = new MarkupContext($token, $this);
                $context->start       = $token[Lexer::AT];
                $context->end         = $token[Lexer::AT] + $token[Lexer::LENGTH];
                $context->text        = $token[Lexer::VALUE];
                $context->replacement = '{{';

                $this->markups[] = $context;

                break;

            case Lexer::T_MARKUP_OPEN:
                $context        = new MarkupContext($token, $this);
                $context->start = $token[Lexer::AT];

                $this->markups[] = $context;

                return $context;
        }

        return $this;
    }
}
