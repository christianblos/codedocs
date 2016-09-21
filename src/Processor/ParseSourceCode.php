<?php
namespace CodeDocs\Processor;

use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\SourceCode\CodeParser;
use CodeDocs\State;
use CodeDocs\Tag;

/**
 * Parses all classes, methods, properties and so on
 *
 * @Tag("defaultProcessor")
 */
class ParseSourceCode implements ProcessorInterface
{
    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws \RuntimeException
     */
    public function run(State $state, Logger $logger)
    {
        $codeParser = new CodeParser();

        $cacheDir  = $state->config->getCacheDir();
        $cacheNote = $cacheDir ? 'cache at ' . $cacheDir : 'cache disabled';

        $logger->log(0, sprintf('parse class dirs (%s)', $cacheNote));

        $classes = $codeParser->parse($state->config->getClassDirs(), $state->config->getCacheDir());

        if ($logger->hasDepth(1)) {
            foreach ($classes as $class) {
                $logger->log(1, sprintf('found class <cyan>%s<reset> in %s', $class->name, $class->fileName));
            }
        }

        $state->classes = $classes;
    }
}
