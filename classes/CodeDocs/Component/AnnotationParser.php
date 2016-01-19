<?php
namespace CodeDocs\Component;

use CodeDocs\Annotation\Annotation;
use CodeDocs\Annotation\ContentInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

/**
 * Parses class and extracts all annotations
 */
class AnnotationParser
{
    /**
     * @var SimpleAnnotationReader
     */
    private $reader;

    /**
     * @param SimpleAnnotationReader $reader
     */
    public function __construct(SimpleAnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $namespace
     * @param string $path
     */
    public function registerNamespace($namespace, $path)
    {
        $this->reader->addNamespace($namespace);
        AnnotationRegistry::registerAutoloadNamespace($namespace, $path);
        AnnotationRegistry::registerAutoloadNamespace('\\' . $namespace, $path);
    }

    /**
     * @param string $className
     *
     * @return Annotation[]
     */
    public function extractAnnotations($className)
    {

        $class = new ReflectionClass($className);

        $annotations = $this->reader->getClassAnnotations($class);

        $annotations = array_filter($annotations, function ($annotation) {
            return $annotation instanceof Annotation;
        });

        $this->extendAnnotations($annotations, $class);

        foreach ($class->getMethods() as $method) {
            $methodAnnotations = $this->reader->getMethodAnnotations($method);
            $this->extendAnnotations($methodAnnotations, $method);

            $annotations = array_merge($annotations, $methodAnnotations);
        }

        foreach ($class->getProperties() as $property) {
            $propertyAnnotations = $this->reader->getPropertyAnnotations($property);
            $this->extendAnnotations($propertyAnnotations, $property);

            $annotations = array_merge($annotations, $propertyAnnotations);
        }

        return $annotations;
    }

    /**
     * @param Annotation[] $annotations
     * @param Reflector    $reflector
     */
    private function extendAnnotations(array $annotations, Reflector $reflector)
    {
        foreach ($annotations as $annotation) {
            $this->applyReflectorData($annotation, $reflector);

            if ($annotation instanceof ContentInterface) {
                $this->applyContent($annotation, $reflector);
            }
        }
    }

    /**
     * @param Annotation $annotation
     * @param Reflector  $reflector
     */
    private function applyReflectorData(Annotation $annotation, Reflector $reflector)
    {
        if ($reflector instanceof ReflectionClass) {
            $annotation->originClass = $reflector->getName();
        } elseif ($reflector instanceof ReflectionMethod) {
            $annotation->originClass  = $reflector->class;
            $annotation->originMethod = $reflector->getName();
        } elseif ($reflector instanceof ReflectionProperty) {
            $annotation->originClass    = $reflector->class;
            $annotation->originProperty = $reflector->getName();
        }
    }

    /**
     * @param ContentInterface $annotation
     * @param Reflector        $reflector
     */
    private function applyContent(ContentInterface $annotation, Reflector $reflector)
    {
        if ($reflector instanceof ReflectionClass) {
            $docComment = $reflector->getDocComment();
        } elseif ($reflector instanceof ReflectionMethod) {
            $docComment = $reflector->getDocComment();
        } else {
            // not supported reflector
            return;
        }

        $content = $this->extractContent($docComment, get_class($annotation));
        $annotation->setContent($content);
    }

    /**
     * Extract content below doc comment annotation
     *
     * @param string $docComment
     * @param string $annotationClass
     *
     * @return string
     */
    private function extractContent($docComment, $annotationClass)
    {
        $isRecording = false;
        $content     = '';

        // remove doc comment stars at line start
        $docComment = preg_replace('/^[ \t]*(\*\/? ?|\/\*\*)/m', '', $docComment);

        $search = '(' .
            preg_quote($annotationClass, '/')
            . '|'
            . substr($annotationClass, strrpos($annotationClass, '\\') + 1)
            . ')';

        $lines = preg_split('/\n|\r\n/', $docComment);
        foreach ($lines as $line) {
            if (preg_match('/^@' . $search . '(\s|\(|$)/', $line)) {
                $isRecording = true;
            } elseif ($isRecording) {
                if (preg_match('/^@/', $line)) {
                    break;
                }
                $content .= $line . PHP_EOL;
            }
        }

        return trim($content);
    }
}
