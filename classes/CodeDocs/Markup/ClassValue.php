<?php
namespace CodeDocs\Markup;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Parsable;
use ReflectionClass;

/**
 * @CodeDocs\Topic(file="02.usage/03.markups/00.ClassValue/docs.md")
 *
 * ---
 * title: ClassValue-Markup
 * taxonomy:
 *     category: docs
 * ---
 *
 * The **ClassValue**-Markup will extract the value of a **class constant** or **property**.
 *
 * ## Usage
 *
 * Show a value of a class constant:
 *
 * ```md
 * ClassValue("SomeClass::SOME_CONSTANT")
 * ```
 *
 * Show a default value of a property:
 *
 * ```md
 * ClassValue("SomeClass::$someProperty")
 * ```
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/ClassValue/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/ClassValue/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/ClassValue/export/example.md")}
 * ```
 *
 *
 * @CodeDocs\ListItem(list="markups", label="ClassValue", link="/usage/markups/ClassValue")
 *
 * @Annotation
 */
class ClassValue extends Markup
{

    /**
     * @var string
     */
    public $value;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $pos = strpos($this->value, '::');

        if (!$pos) {
            throw new MarkupException('invalid class constant definition');
        }

        $class = substr($this->value, 0, $pos);
        $var   = substr($this->value, $pos + 2);

        if (strpos($var, '$') === 0) {
            $var   = substr($var, 1);
            $props = (new ReflectionClass($class))->getDefaultProperties();

            if (!array_key_exists($var, $props)) {
                throw new MarkupException(sprintf('property %s does not exist in class %s', $var, $class));
            }

            $value = $props[$var];
        } else {
            $value = (new ReflectionClass($class))->getConstant($var);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif ($value === null) {
            return 'null';
        }

        return (string)$value;
    }
}
