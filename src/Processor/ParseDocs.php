<?php
namespace CodeDocs\Processor;

use CodeDocs\Doc\Invoker;
use CodeDocs\Doc\Lexer;
use CodeDocs\Doc\MarkupParser;
use CodeDocs\Doc\Parser\FileContext;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func;
use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\RunnerConfig;
use CodeDocs\State;
use CodeDocs\Tag;
use CodeDocs\Type\Parsable;
use RuntimeException;

/**
 * Parses the documentation and replaces all Markups
 *
 * @Tag("defaultProcessor")
 */
class ParseDocs implements ProcessorInterface
{
    /**
     * @var MarkupParser
     */
    private $markupParser;

    /**
     * @var Invoker
     */
    private $invoker;

    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws RuntimeException
     * @throws MarkupException
     */
    public function run(State $state, Logger $logger)
    {
        $this->initDependencies();
        $this->initFunctions($state->config);

        $cacheDir  = $state->config->getCacheDir();
        $cacheNote = $cacheDir ? 'cache at ' . $cacheDir : 'cache disabled';

        $logger->log(0, sprintf('parse documentation (%s)', $cacheNote));

        $files = $this->markupParser->parse($state->config->getExportDir(), $state->config->getCacheDir());

        foreach ($files as $file) {
            $this->replaceMarkupsInFile($file, $state, $logger);
        }
    }

    /**
     *
     */
    private function initDependencies()
    {
        $this->markupParser = new MarkupParser(new Lexer(), new Filesystem());
        $this->invoker      = new Invoker();
    }

    /**
     * @param RunnerConfig $config
     */
    private function initFunctions(RunnerConfig $config)
    {
        $this->invoker->define(Func\Classes::FUNC_NAME, new Func\Classes());
        $this->invoker->define(Func\CodeSnippet::FUNC_NAME, new Func\CodeSnippet());
        $this->invoker->define(Func\Constants::FUNC_NAME, new Func\Constants());
        $this->invoker->define(Func\DefaultValue::FUNC_NAME, new Func\DefaultValue());
        $this->invoker->define(Func\DocComment::FUNC_NAME, new Func\DocComment());
        $this->invoker->define(Func\File::FUNC_NAME, new Func\File());
        $this->invoker->define(Func\FileContent::FUNC_NAME, new Func\FileContent());
        $this->invoker->define(Func\Join::FUNC_NAME, new Func\Join());
        $this->invoker->define(Func\JsonValue::FUNC_NAME, new Func\JsonValue());
        $this->invoker->define(Func\MethodParamsTable::FUNC_NAME, new Func\MethodParamsTable());
        $this->invoker->define(Func\NotTagged::FUNC_NAME, new Func\NotTagged());
        $this->invoker->define(Func\Param::FUNC_NAME, new Func\Param());
        $this->invoker->define(Func\Parse::FUNC_NAME, new Func\Parse());
        $this->invoker->define(Func\Relpath::FUNC_NAME, new Func\Relpath());
        $this->invoker->define(Func\RenderList::FUNC_NAME, new Func\RenderList());
        $this->invoker->define(Func\Replace::FUNC_NAME, new Func\Replace());
        $this->invoker->define(Func\ShortName::FUNC_NAME, new Func\ShortName());
        $this->invoker->define(Func\Table::FUNC_NAME, new Func\Table());
        $this->invoker->define(Func\Tagged::FUNC_NAME, new Func\Tagged());
        $this->invoker->define(Func\Topic::FUNC_NAME, new Func\Topic());

        foreach ($config->getFunctions() as $name => $function) {
            $this->invoker->define($name, $function);
        }
    }

    /**
     * @param FileContext $context
     * @param State       $state
     * @param Logger      $logger
     *
     * @throws MarkupException
     */
    private function replaceMarkupsInFile(FileContext $context, State $state, Logger $logger)
    {
        $logger->log(1, sprintf('replace markups in <cyan>%s<reset>', $context->path));

        $state->currentFile = $context->path;

        $content = file_get_contents($context->path);
        $content = $this->replaceMarkupsInContent($content, $context, $state, $logger, true);

        file_put_contents($context->path, $content);
    }

    /**
     * @param string      $content
     * @param FileContext $context
     * @param State       $state
     * @param Logger      $logger
     * @param bool        $catch
     *
     * @return string
     * @throws MarkupException
     */
    private function replaceMarkupsInContent($content, FileContext $context, State $state, Logger $logger, $catch)
    {
        $offset = 0;

        foreach ($context->markups as $markup) {
            try {
                $logger->log(2, sprintf('replace <yellow>%s<reset>', $markup->text));

                if ($markup->func === null) {
                    $value = $markup->replacement;
                } else {
                    $value = $this->invoker->call($markup->func, $state);
                }

                if ($value instanceof Parsable) {
                    $value = $this->replaceMarkupsInParsable($value, $context->path, $state, $logger);
                } elseif (is_array($value)) {
                    $value = implode(PHP_EOL, $value);
                } elseif ($value !== null && !is_scalar($value)) {
                    throw new MarkupException(
                        sprintf(
                            'last return value should be string or %s. But %s was given',
                            Parsable::class,
                            gettype($value)
                        ),
                        null,
                        $markup->at
                    );
                }

                $length = $markup->end - $markup->start;

                $content = substr_replace($content, $value, $markup->start + $offset, $length);

                $offset += strlen($value) - $length;
            } catch (MarkupException $ex) {
                if ($catch) {
                    throw new MarkupException($ex->getMessage(), $context->path, $markup->func->at);
                } else {
                    throw $ex;
                }
            }
        }

        return $content;
    }

    /**
     * @param Parsable $parsable
     * @param string   $path
     * @param State    $state
     * @param Logger   $logger
     *
     * @return string
     * @throws MarkupException
     */
    private function replaceMarkupsInParsable(Parsable $parsable, $path, State $state, Logger $logger)
    {
        $logger->log(1, sprintf('replace markups in <yellow>result of <cyan>%s<reset>', $path));

        $context = $this->markupParser->parseContent($parsable->text, $path);

        return $this->replaceMarkupsInContent($parsable->text, $context, $state, $logger, false);
    }
}
