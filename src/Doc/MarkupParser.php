<?php
namespace CodeDocs\Doc;

use CodeDocs\BaseParser;
use CodeDocs\Doc\Parser\FileContext;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Exception\TokenException;
use CodeDocs\Helper\Filesystem;
use RuntimeException;
use SplFileInfo;

class MarkupParser extends BaseParser
{
    const CACHE_FILE = 'codedocs.markups.cache';

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @param Lexer      $lexer
     * @param Filesystem $filesystem
     */
    public function __construct(Lexer $lexer, Filesystem $filesystem)
    {
        $this->lexer = $lexer;

        parent::__construct($filesystem);
    }

    /**
     * @param string      $dir
     * @param string|null $cacheDir
     *
     * @return FileContext[]
     * @throws RuntimeException
     */
    public function parse($dir, $cacheDir = null)
    {
        return $this->parseFiles([$dir], '/\.md$/', $cacheDir);
    }

    /**
     * @param string $content
     * @param string $filePath
     *
     * @return FileContext
     * @throws MarkupException
     */
    public function parseContent($content, $filePath)
    {
        $this->lexer->setInput($content);

        $context = new FileContext($filePath);

        try {
            while ($token = $this->lexer->getNext()) {
                $newContext = $context->enterToken($token, $content);

                if ($newContext === null) {
                    throw new TokenException('unexpected token', $token);
                }

                $context = $newContext;
            }
        } catch (TokenException $ex) {
            $at = $ex->getToken()[Lexer::AT];

            throw new MarkupException($ex->getMessage(), $filePath, $at);
        } catch (\Exception $ex) {
            throw new MarkupException($ex->getMessage());
        }

        return $context;
    }

    /**
     * @return string
     */
    protected function getCacheFile()
    {
        return self::CACHE_FILE;
    }

    /**
     * @param string $path
     *
     * @return mixed
     * @throws MarkupException
     */
    protected function parseFile($path)
    {
        $content = $this->filesystem->readFile($path);

        return $this->parseContent($content, $path);
    }

    /**
     * @param FileContext $result
     * @param SplFileInfo $file
     *
     * @return mixed
     */
    protected function fromCache($result, SplFileInfo $file)
    {
        $result->path = $file->getRealPath();

        return $result;
    }
}
