<?php
namespace CodeDocs\Component;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Markup\Markup;
use Doctrine\Common\Annotations\DocParser;

class MarkupParser
{
    /**
     * @var DocParser
     */
    private $docParser;

    /**
     * @var string[]
     */
    private $markupNamespaces = ['CodeDocs\Markup'];

    /**
     * @param DocParser $docParser
     */
    public function __construct(DocParser $docParser)
    {
        $this->docParser = $docParser;
    }

    /**
     * @param string $namespace
     */
    public function addMarkupNamespace($namespace)
    {
        $this->markupNamespaces[] = $namespace;
    }

    /**
     * @param string $content
     *
     * @return Markup[]
     */
    public function getMarkups($content)
    {
        $found = preg_match_all('/\{@(([\w\\\\]+)\(.*\))\}/U', $content, $matches);
        if ($found <= 0) {
            return [];
        }

        $markups = [];

        foreach ($matches[1] as $idx => $markup) {
            $prefix      = $this->getMarkupPrefix($matches[2][$idx]);
            $annotations = $this->docParser->parse('@' . $prefix . $markup);
            $annotation  = array_shift($annotations);

            if (!$annotation || !$annotation instanceof Markup) {
                throw new MarkupException('annotation for markup ' . $markup . ' must be a ' . Markup::class);
            }

            $annotation->setMarkupString($matches[0][$idx]);

            $markups[] = $annotation;
        }

        return $markups;
    }

    /**
     * @param string $markup
     *
     * @return string
     */
    private function getMarkupPrefix($markup)
    {
        foreach ($this->markupNamespaces as $namespace) {
            $class = $namespace . '\\' . $markup;

            if (class_exists($class)) {
                return $namespace . '\\';
            }
        }

        return '';
    }
}
