<?php
namespace CodeDocs\Annotation;

use CodeDocs\Exception\AnnotationException;
use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @CodeDocs\Topic(id="extend-create-annotation")
 *
 * ---
 * title: Create an Annotation
 * taxonomy:
 * category: docs
 * ---
 *
 * **Annotation** classes are simple classes with just public properties. We're using the
 * [Doctrine Annotation Library](http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html)
 * to parse annotations. If you're not familiar with this library, it may help if you have a look into it first.
 *
 * Your annotation must follow some rules so it can be recognized as an annotation:
 *
 * - it must extend from **\CodeDocs\Annotation\Annotation**.
 * - it must have the **@Annotation** annotation.
 *
 * ## Namespaces
 *
 * If your annotations are in your own namespace, you should also add it to the **{@ConfigParam("configFile")}**.
 * For each namespace you have to specify a path following the [PSR-0 Standard](http://www.php-fig.org/psr/psr-0/):
 *
 * ```yaml
 * annotationNamespaces:
 *   My\Annotation: annotations
 * ```
 *
 * >>>>> The namespace paths are always relative to the directory where the {@ConfigParam("configFile")} lies.
 *
 * ## Example
 *
 * Here's an example annotation:
 *
 * ```php
 * {@FileContent("examples/extend/annotation/annotations/My/Annotation/Example.php")}
 * ```
 *
 * You can use it with the full namespace...
 *
 * ```php
 * {@FileContent("examples/extend/annotation/classes/ExampleFullNamespace.php")}
 * ```
 *
 * ... or with a use statement.
 *
 * ```php
 * {@FileContent("examples/extend/annotation/classes/ExampleImport.php")}
 * ```
 *
 * The string "this is a test" will be put inside the **$value** property.
 * **$something** will have the value "foo".
 *
 * You can see that the {@ConfigParam("projectName")}-Parser can find this annotation by
 * having a look at the command output. Execute `{@ConfigParam("executable")} -vvv` and
 * see something like:
 *
 * ```
 *  ...
 * > extract annotations...
 *    > ...of class \ExampleFullNamespace
 *       > found My\Annotation\Example
 *    > ...of class \ExampleImport
 *       > found My\Annotation\Example
 *  ...
 * ```
 */
class Annotation extends DoctrineAnnotation
{
    /**
     * @var string
     */
    public $originClass;

    /**
     * @var string
     */
    public $originMethod;

    /**
     * @var string
     */
    public $originProperty;

    /**
     * @return ReflectionClass|ReflectionMethod|ReflectionProperty
     */
    public function getReflector()
    {
        if (!$this->originClass) {
            throw new AnnotationException('no originClass set');
        }

        if ($this->originMethod) {
            return new ReflectionMethod($this->originClass, $this->originMethod);
        }

        if ($this->originProperty) {
            return new ReflectionProperty($this->originClass, $this->originProperty);
        }

        return new ReflectionClass($this->originClass);
    }
}
