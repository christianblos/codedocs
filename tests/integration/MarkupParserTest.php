<?php
namespace CodeDocs\Test\Integration;

use CodeDocs\Doc\Lexer;
use CodeDocs\Doc\MarkupParser;
use CodeDocs\Doc\Parser\FileContext;
use CodeDocs\Doc\Parser\FuncContext;
use CodeDocs\Doc\Parser\MarkupContext;
use CodeDocs\Helper\Filesystem;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class MarkupParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem|MockObject
     */
    private $filesystem;

    /**
     * @var MarkupParser
     */
    private $parser;

    /**
     * @var string
     */
    private $fakeFileName = 'someFile';

    /**
     * @var string
     */
    private $fakeFileHash = 'someHash';

    protected function setUp()
    {
        $lexer = new Lexer();

        $this->filesystem = $this->createMock(Filesystem::class);

        $this->parser = new MarkupParser($lexer, $this->filesystem);
    }

    public function testNoMarkup()
    {
        $this->assertParsed('foo bar', $this->createFileContext());
    }

    public function testMarkup()
    {
        $markupStr = '{{ foo(val1:99, val2: "string") }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $markupStr, 0);

        $markup->func         = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);
        $markup->func->params = ['val1' => 99, 'val2' => 'string'];

        $expected->markups = [$markup];

        $this->assertParsed($markupStr, $expected);
    }

    public function testNestedFunction()
    {
        $markupStr = '{{ foo(sub: bar(val: 3)) }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $markupStr, 0);

        $markup->func = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);

        $subFunc         = new FuncContext([Lexer::AT => 12, Lexer::VALUE => 'bar'], $markup->func);
        $subFunc->params = [
            'val' => 3,
        ];

        $markup->func->params = [
            'sub' => $subFunc,
        ];

        $expected->markups = [$markup];

        $this->assertParsed($markupStr, $expected);
    }

    /**
     * @return FileContext
     */
    private function createFileContext()
    {
        return new FileContext($this->fakeFileName);
    }

    /**
     * @param FileContext $context
     * @param string      $markupStr
     * @param int         $startPos
     *
     * @return MarkupContext
     */
    private function createMarkup(FileContext $context, $markupStr, $startPos)
    {
        $markup        = new MarkupContext([Lexer::AT => $startPos], $context);
        $markup->start = $startPos;
        $markup->end   = $startPos + strlen($markupStr);
        $markup->text  = $markupStr;

        $context->markups[] = $markup;

        return $markup;
    }

    /**
     * @param string      $content
     * @param FileContext $expected
     *
     * @return FileContext[]
     */
    private function assertParsed($content, FileContext $expected)
    {
        $file = $this->createMock(\SplFileInfo::class);

        $file->expects(self::any())
            ->method('getRealPath')
            ->willReturn($this->fakeFileName);

        $this->filesystem
            ->expects(self::any())
            ->method('getFilesOfDirs')
            ->willReturn([$file]);

        $this->filesystem
            ->expects(self::any())
            ->method('readFile')
            ->willReturn($content);

        $this->filesystem
            ->expects(self::any())
            ->method('getFileHash')
            ->willReturn($this->fakeFileHash);

        $parsed = $this->parser->parse('dir');

        self::assertEquals([$this->fakeFileHash => $expected], $parsed);
    }
}
