<?php
namespace CodeDocs\SourceCode;

use CodeDocs\BaseParser;
use CodeDocs\Helper\Filesystem;
use CodeDocs\SourceCode\Ref\RefClass;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use RuntimeException;

class CodeParser extends BaseParser
{
    const CACHE_FILE = 'codedocs.refs.cache';

    /**
     * @var Parser
     */
    private $phpParser;

    /**
     * @var NodeTraverserInterface
     */
    private $traverser;

    /**
     * @param Parser        $phpParser
     * @param NodeTraverser $traverser
     * @param Filesystem    $filesystem
     */
    public function __construct(
        Parser $phpParser = null,
        NodeTraverser $traverser = null,
        Filesystem $filesystem = null
    ) {
        if ($phpParser === null) {
            $phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        }

        if ($traverser === null) {
            $traverser = new NodeTraverser();
        }

        if ($filesystem === null) {
            $filesystem = new Filesystem();
        }

        $this->phpParser = $phpParser;
        $this->traverser = $traverser;

        parent::__construct($filesystem);
    }

    /**
     * @param string[]    $dirs
     * @param string|null $cacheDir
     *
     * @return RefClass[]
     * @throws RuntimeException
     */
    public function parse(array $dirs, $cacheDir = null)
    {
        $results = $this->parseFiles($dirs, '/\.php$/', $cacheDir);
        $classes = [];

        foreach ($results as $refs) {
            foreach ($refs as $className => $ref) {
                $classes[$className] = $ref;
            }
        }

        return $classes;
    }

    /**
     * @return string
     */
    protected function getCacheFile()
    {
        return self::CACHE_FILE;
    }

    /**
     * @param string $file
     *
     * @return RefClass[]
     * @throws RuntimeException
     */
    protected function parseFile($file)
    {
        $stmts = $this->phpParser->parse(file_get_contents($file));

        $visitor = new RefVisitor($file);
        $this->traverser->addVisitor($visitor);
        $this->traverser->traverse($stmts);
        $this->traverser->removeVisitor($visitor);

        return $visitor->getClasses();
    }
}
