<?php
namespace CodeDocs\SourceCode;

use CodeDocs\Annotation;
use CodeDocs\ContentInterface;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\SourceCode\Ref\RefComment;
use Doctrine\Common\Annotations\DocParser;
use InvalidArgumentException;
use RuntimeException;

class AnnotationParser
{
    /**
     * @var DocParser
     */
    private $docParser;

    /**
     * @param DocParser $docParser
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(DocParser $docParser)
    {
        $this->docParser = $docParser;
        $this->docParser->setIgnoreNotImportedAnnotations(true);
        $this->docParser->addNamespace('');
        $this->registerNamespace('CodeDocs');
    }

    /**
     * @param string $namespace
     *
     * @throws RuntimeException
     */
    public function registerNamespace($namespace)
    {
        $namespace = ltrim($namespace, '\\');
        $this->docParser->addNamespace($namespace);
        $this->docParser->addNamespace('\\' . $namespace);
    }

    /**
     * @param RefClass $class
     *
     * @return Annotation[]
     */
    public function parse(RefClass $class)
    {
        $result = [];

        $annotations = $this->parseComment($class->docComment);
        foreach ($annotations as $annotation) {
            $annotation->originClass = $class->name;
            $result[]                = $annotation;
        }

        foreach ($class->constants as $constant) {
            foreach ($this->parseComment($constant->docComment) as $annotation) {
                $annotation->originClass    = $class->name;
                $annotation->originConstant = $constant->name;
                $result[]                   = $annotation;
            }
        }

        foreach ($class->properties as $property) {
            foreach ($this->parseComment($property->docComment) as $annotation) {
                $annotation->originClass    = $class->name;
                $annotation->originProperty = $property->name;
                $result[]                   = $annotation;
            }
        }

        foreach ($class->methods as $method) {
            foreach ($this->parseComment($method->docComment) as $annotation) {
                $annotation->originClass  = $class->name;
                $annotation->originMethod = $method->name;
                $result[]                 = $annotation;
            }
        }

        return $result;
    }

    /**
     * @param RefComment $comment
     *
     * @return Annotation[]
     */
    private function parseComment(RefComment $comment = null)
    {
        if ($comment === null) {
            return [];
        }

        $annotations = $this->docParser->parse($comment->text);

        foreach ($annotations as $key => $annotation) {
            if (!$annotation instanceof Annotation) {
                unset($annotations[$key]);
                continue;
            }

            if ($annotation instanceof ContentInterface) {
                $content = $this->extractContent($comment->text, get_class($annotation));
                $annotation->setContent($content);
            }
        }

        return $annotations;
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
