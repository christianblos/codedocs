<?php
namespace CodeDocs\Processor;

use CodeDocs\Exception\AnnotationException;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\SourceCode\AnnotationParser;
use CodeDocs\State;
use CodeDocs\Tag;
use Doctrine\Common\Annotations\DocParser;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Parses all annotations in the source code
 *
 * @Tag("defaultProcessor")
 */
class ParseAnnotations implements ProcessorInterface
{
    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws AnnotationException
     */
    public function run(State $state, Logger $logger)
    {
        $annotationParser = new AnnotationParser(new DocParser());

        $logger->log(0, 'parse annotations');

        foreach ($state->config->getAnnotationNamespaces() as $namespace) {
            $annotationParser->registerNamespace($namespace);
        }

        foreach ($state->classes as $class) {
            try {
                $annotations = $annotationParser->parse($class);
            } catch (Exception $ex) {
                throw new AnnotationException(sprintf(
                    'failed to parse annotations of class %s. %s',
                    $class->name,
                    $ex->getMessage()
                ));
            }

            $logger->log(1, sprintf('of class %s', $class->name));

            foreach ($annotations as $annotation) {
                $logger->log(2, sprintf('found: <cyan>%s<reset>', get_class($annotation)));
                $state->annotations[] = $annotation;
            }
        }
    }
}
