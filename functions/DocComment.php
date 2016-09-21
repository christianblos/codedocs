<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func\Helper\RefTrait;

/**
 * @CodeDocs\Topic(file="functions/docComment.md")
 *
 * Returns the doc comment of a class, method or class member.
 *
 * #### Parameters
 *
 * | Name               | Type   | Description
 * | ------------------ | -------| ------------
 * | of                 | string | The class name, method or class member
 * | excludeAnnotations | bool   | (Optional) True to hide annotations
 * | firstLine          | bool   | (Optional) True to only return the first line
 *
 * #### Example
 *
 * ```
 * \{{ docComment(of:'SomeClass') }}
 * \{{ docComment(of:'SomeClass::$someProperty') }}
 * ```
 */
class DocComment extends MarkupFunction
{
    use RefTrait;

    const FUNC_NAME = 'docComment';

    /**
     * @param string $of
     * @param bool   $excludeAnnotations
     * @param bool   $firstLine
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($of, $excludeAnnotations = false, $firstLine = false)
    {
        $ref = $this->getRef($this->state, $of);

        if (!$ref->docComment) {
            return '';
        }

        $content = $ref->docComment->text;
        $content = preg_replace('/^\s*\/\*\*/', '', $content);
        $content = preg_replace('/\s*\*\/\s*$/', '', $content);
        $content = preg_replace('/^\s*\*\s?/m', '', $content);
        $content = trim($content);

        if ($excludeAnnotations) {
            $content = $this->removeAnnotationLines($content);
        }

        if ($firstLine) {
            $content = $this->getFirstLine($content);
        }

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function removeAnnotationLines($content)
    {
        $lines = preg_split('/\r\n|\n/', $content);

        $lines = array_filter($lines, function ($line) {
            return strpos(trim($line), '@') !== 0;
        });

        return implode(PHP_EOL, $lines);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function getFirstLine($content)
    {
        $lines = preg_split('/\r\n|\n/', $content);

        foreach ($lines as $line) {
            if (trim($line)) {
                return $line;
            }
        }

        return '';
    }
}
