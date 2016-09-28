<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func\Helper\RefTrait;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\SourceCode\Ref\RefMethod;
use CodeDocs\SourceCode\Ref\RefProperty;

/**
 * Returns a code snippet of a class, method or class member.
 */
class CodeSnippet extends MarkupFunction
{
    use RefTrait;

    const FUNC_NAME = 'codeSnippet';

    /**
     * @param string $of      A class, method, or a class member
     * @param bool   $comment True to also return the doc comment
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($of, $comment = false)
    {
        $ref = $this->getRef($this->state, $of);

        if ($ref instanceof RefClass) {
            $fileName   = $ref->fileName;
            $startLine  = $ref->startLine;
            $endLine    = $ref->endLine;
            $docComment = $ref->docComment;
        } elseif ($ref instanceof RefMethod) {
            $fileName   = $ref->class->fileName;
            $startLine  = $ref->startLine;
            $endLine    = $ref->endLine;
            $docComment = $ref->docComment;
        } elseif ($ref instanceof RefProperty) {
            $fileName   = $ref->class->fileName;
            $startLine  = $ref->line;
            $endLine    = $ref->line;
            $docComment = $ref->docComment;
        } else {
            return '';
        }

        $content = '';

        if ($comment && $docComment) {
            $content .= preg_replace('/^\s*\*/m', ' *', $docComment->text) . PHP_EOL;
        }

        $content .= $this->extractLines($fileName, $startLine, $endLine);

        return $content;
    }

    /**
     * @param string $file
     * @param int    $start
     * @param int    $end
     *
     * @return string
     */
    private function extractLines($file, $start, $end)
    {
        $fileObject = new \SplFileObject($file);
        $fileObject->seek($start - 1);

        $whitespaceLength = null;
        $content          = '';
        $iterator         = $end - $start;

        while ($line = $fileObject->current()) {
            if ($whitespaceLength === null && preg_match('/^\s*/', $line, $matches)) {
                $whitespaceLength = strlen($matches[0]);
            }

            $line = substr($line, $whitespaceLength);
            $content .= $line ?: PHP_EOL;
            $fileObject->next();
            $iterator--;

            if ($iterator < 0) {
                break;
            }
        }

        return trim($content);
    }
}
