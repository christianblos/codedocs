<?php
namespace CodeDocs\Test\Integration;

use CodeDocs\Doc\Lexer;
use CodeDocs\Doc\MarkupParser;
use CodeDocs\Doc\Parser\ArrayContext;
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

    public function testMarkupWithoutParams()
    {
        $content = '{{ foo() }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $content, 0);

        $markup->func         = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);
        $markup->func->params = [];

        $expected->markups = [$markup];

        $this->assertParsed($content, $expected);
    }

    public function testMarkupParams()
    {
        $content = '{{ foo(int:99, float:1.2, str: "string", bool: true, none: null) }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $content, 0);

        $markup->func         = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);
        $markup->func->params = [
            'int'   => 99,
            'float' => 1.2,
            'str'   => 'string',
            'bool'  => true,
            'none'  => null,
        ];

        $expected->markups = [$markup];

        $this->assertParsed($content, $expected);
    }

    public function testMarkupWithArrayParam()
    {
        $content = '{{ foo(arr:["val", "one" => 1, 2 => true, \'empty\' => null]) }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $content, 0);

        $markup->func = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);

        $array         = new ArrayContext([Lexer::AT => 11], $markup->func);
        $array->values = [
            0       => 'val',
            'one'   => 1,
            2       => true,
            'empty' => null,
        ];

        $markup->func->params = ['arr' => $array];

        $expected->markups = [$markup];

        $this->assertParsed($content, $expected);
    }

    public function testNestedFunction()
    {
        $content = '{{ foo(sub: bar(val: 3)) }}';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, $content, 0);

        $markup->func = new FuncContext([Lexer::AT => 3, Lexer::VALUE => 'foo'], $markup);

        $subFunc         = new FuncContext([Lexer::AT => 12, Lexer::VALUE => 'bar'], $markup->func);
        $subFunc->params = [
            'val' => 3,
        ];

        $markup->func->params = [
            'sub' => $subFunc,
        ];

        $expected->markups = [$markup];

        $this->assertParsed($content, $expected);
    }

    public function testAnyText()
    {
        $content = 'true 12.1 "text. {{ foo() }} Other stuff"';

        $expected = $this->createFileContext();

        $markup = $this->createMarkup($expected, '{{ foo() }}', 17);

        $markup->func         = new FuncContext([Lexer::AT => 20, Lexer::VALUE => 'foo'], $markup);
        $markup->func->params = [];

        $expected->markups = [$markup];

        $this->assertParsed($content, $expected);
    }

    public function testEscapedMarkup()
    {
        $content = '\\{{ foo() }} {{ bar() }}';

        $expected = $this->createFileContext();

        $escaped              = $this->createMarkup($expected, '\\{{', 0);
        $escaped->replacement = '{{';

        $markup               = $this->createMarkup($expected, '{{ bar() }}', 13);
        $markup->func         = new FuncContext([Lexer::AT => 16, Lexer::VALUE => 'bar'], $markup);
        $markup->func->params = [];

        $expected->markups = [$escaped, $markup];

        $this->assertParsed($content, $expected);
    }

    public function testEscapedMarkupWithAnyTextBefore()
    {
        $content = 'some \\{{ foo() }} {{ bar() }}';

        $expected = $this->createFileContext();

        $escaped              = $this->createMarkup($expected, '\\{{', 5);
        $escaped->replacement = '{{';

        $markup               = $this->createMarkup($expected, '{{ bar() }}', 18);
        $markup->func         = new FuncContext([Lexer::AT => 21, Lexer::VALUE => 'bar'], $markup);
        $markup->func->params = [];

        $expected->markups = [$escaped, $markup];

        $this->assertParsed($content, $expected);
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
