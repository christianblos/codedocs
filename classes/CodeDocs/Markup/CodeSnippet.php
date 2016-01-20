<?php
namespace CodeDocs\Markup;

use CodeDocs\Component\Config;
use CodeDocs\Component\ParseResult;
use CodeDocs\ValueObject\Parsable;
use ReflectionClass;
use ReflectionMethod;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.CodeSnippet/docs.md")
 *
 * The **CodeSnippet**-Markup will extract a code snippet from a class or method.
 *
 * ## Usage
 *
 * ```md
 * CodeSnippet("SomeClass::someMethod", comment=true)
 * ```
 *
 * ## Parameters
 *
 * The first param is the class ("SomeClass") or method ("SomeClass::someMethod")
 * that should be displayed.
 *
 * ##### comment
 *
 * Set this to true to also extract the doc comment of this class or method.
 *
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/CodeSnippet/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/CodeSnippet/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/CodeSnippet/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="CodeSnippet", link="/usage/markups/CodeSnippet")
 *
 * @Annotation
 */
class CodeSnippet extends Markup
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var bool
     */
    public $comment = false;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     *
     * @return string|Parsable
     */
    public function buildContent(ParseResult $parseResult, Config $config)
    {
        $pos = strpos($this->value, '::');

        if ($pos) {
            $class  = substr($this->value, 0, $pos);
            $method = substr($this->value, $pos + 2);
            $ref    = new ReflectionMethod($class, $method);
        } else {
            $ref = new ReflectionClass($this->value);
        }

        $content = '';

        if ($this->comment) {
            $content .= preg_replace('/^\s*\*/m', ' *', $ref->getDocComment()) . PHP_EOL;
        }

        $content .= $this->extractLines(
            $ref->getFileName(),
            $ref->getStartLine(),
            $ref->getEndLine()
        );

        return $content;
    }

    /**
     * @param string $file
     * @param int    $start
     * @param int    $end
     *
     * @return string
     */
    public function extractLines($file, $start, $end)
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
            $content .= $line ? $line : PHP_EOL;

            $fileObject->next();

            $iterator--;
            if ($iterator < 0) {
                break;
            }
        }

        return trim($content);
    }
}
