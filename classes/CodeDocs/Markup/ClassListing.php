<?php
namespace CodeDocs\Markup;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\ItemList;
use CodeDocs\ValueObject\Parsable;
use ReflectionClass;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.ClassListing/docs.md")
 *
 * The **ClassListing**-Markup will display a list of classes matching the given criteria.
 * The class list will contain all classes parsed by CodeDocs.
 *
 * ## Usage
 *
 * ```md
 * ClassListing(matches="/Controller$/", extends="My\ParentClass", implements="My\SomeInterface", glue=", ")
 * ```
 *
 * ## Parameters
 *
 * ##### glue
 *
 * Will be used as separator for the list. If no glue is given, the list will be displayed in bullet points.
 *
 * ##### matches
 *
 * Only classes matching this regular expression will be displayed.
 *
 * ##### extends
 *
 * Only classes extending from this class will be displayed.
 *
 * ##### implements
 *
 * Only classes implementing this interface will be displayed.
 * You can also define multiple interfaces using a comma separated list.
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/ClassListing/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/ClassListing/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/ClassListing/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="ClassListing", link="/usage/markups/ClassListing")
 *
 * @Annotation
 */
class ClassListing extends Markup
{
    /**
     * @var string
     */
    public $matches;

    /**
     * @var string
     */
    public $extends;

    /**
     * @var string
     */
    public $implements;

    /**
     * @var string|null
     */
    public $glue;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $this->checkExtends();
        $interfaces = $this->getInterfaces();

        $classes = $parseResult->getClasses()
            ->filter(function ($class) use ($interfaces) {
                return
                    $this->filterMatches($class)
                    && $this->filterExtends($class)
                    && $this->filterImplements($class, $interfaces);
            })
            ->toArray();

        return (string)new ItemList($classes, $this->glue);
    }

    /**
     * @throws MarkupException
     */
    protected function checkExtends()
    {
        if ($this->extends && class_exists($this->extends) === false) {
            throw new MarkupException(sprintf('class %s does not exist', $this->extends));
        }
    }

    /**
     * @throws MarkupException
     *
     * @return string[]
     */
    protected function getInterfaces()
    {
        if (!$this->implements) {
            return [];
        }

        $interfaces = array_map('trim', explode(',', $this->implements));

        foreach ($interfaces as $interface) {
            if ($interface && interface_exists($interface) === false) {
                throw new MarkupException(sprintf('interface %s does not exist', $interface));
            }
        }

        return $interfaces;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    protected function filterMatches($class)
    {
        if (!$this->matches) {
            return true;
        }

        return preg_match($this->matches, $class) > 0;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    protected function filterExtends($class)
    {
        if (!$this->extends) {
            return true;
        }

        $ref = new ReflectionClass($class);

        return $ref->isSubclassOf($this->extends);
    }

    /**
     * @param string   $class
     * @param string[] $interfaces
     *
     * @return bool
     */
    protected function filterImplements($class, array $interfaces)
    {
        if (!$interfaces) {
            return true;
        }

        $ref = new ReflectionClass($class);

        foreach ($interfaces as $interface) {
            if (!$ref->implementsInterface($interface)) {
                return false;
            }

            if ($ref->name === ltrim($interface, '\\')) {
                return false;
            }
        }

        return true;
    }
}
