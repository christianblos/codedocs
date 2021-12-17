<?php
namespace CodeDocs\SourceCode;

use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\SourceCode\Ref\RefComment;
use CodeDocs\SourceCode\Ref\RefConstant;
use CodeDocs\SourceCode\Ref\RefMethod;
use CodeDocs\SourceCode\Ref\RefNamespace;
use CodeDocs\SourceCode\Ref\RefParam;
use CodeDocs\SourceCode\Ref\RefProperty;
use CodeDocs\SourceCode\Ref\Visibility;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UnionType;
use PhpParser\NodeVisitor;

use function implode;

class RefVisitor implements NodeVisitor
{
    /**
     * @var RefNamespace
     */
    private $namespace;

    /**
     * @var RefClass|null
     */
    private $class;

    /**
     * @var RefClass[]
     */
    private $classes = [];

    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(array $nodes)
    {
        $this->namespace = new RefNamespace();
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Namespace_) {
            $this->namespace->name = (string)$node->name;
        } elseif ($node instanceof Use_) {
            foreach ($node->uses as $use) {
                $this->namespace->uses[$use->getAlias()->toString()] = $use->name->toString();
            }
        } elseif ($node instanceof Class_) {
            $this->class = $this->createClass($node);
        } elseif ($node instanceof Interface_) {
            $this->class = $this->createInterface($node);
        } elseif ($node instanceof Trait_) {
            $this->class = $this->createTrait($node);
        } elseif ($node instanceof TraitUse) {
            foreach ($node->traits as $name) {
                $this->class->traits[] = $this->getFullClassName($name);
            }
        } elseif ($node instanceof Property) {
            $property = $this->createProperty($node);

            $this->class->properties[$property->name] = $property;
        } elseif ($node instanceof ClassConst) {
            $const = $this->createConstant($node);

            $this->class->constants[$const->name] = $const;
        } elseif ($node instanceof ClassMethod) {
            $method = $this->createMethod($node);

            $this->class->methods[$method->name] = $method;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Namespace_) {
            $this->namespace->name = null;
        } elseif ($node instanceof Class_) {
            $this->class->endLine              = $node->getAttribute('endLine');
            $this->classes[$this->class->name] = $this->class;
            $this->class                       = null;
        } elseif ($node instanceof Interface_) {
            $this->class->endLine              = $node->getAttribute('endLine');
            $this->classes[$this->class->name] = $this->class;
            $this->class                       = null;
        } elseif ($node instanceof Trait_) {
            $this->class->endLine              = $node->getAttribute('endLine');
            $this->classes[$this->class->name] = $this->class;
            $this->class                       = null;
        } elseif ($node instanceof ClassMethod) {
            $this->class->methods[(string)$node->name]->endLine = $node->getAttribute('endLine');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes)
    {
    }

    /**
     * @return RefClass[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param Class_ $node
     *
     * @return RefClass
     */
    private function createClass(Class_ $node)
    {
        $ref = new RefClass();

        $ref->fileName   = $this->file;
        $ref->name       = $this->getFullClassName((string)$node->name);
        $ref->docComment = $this->createDocComment($node);

        $ref->startLine = $node->getLine();

        foreach ($node->implements as $implement) {
            $ref->implements[] = $this->getFullClassName($implement);
        }

        if ($node->extends) {
            $ref->extends = $this->getFullClassName($node->extends);
        }

        $ref->isAbstract  = $node->isAbstract();
        $ref->isAnonymous = $node->isAnonymous();
        $ref->isFinal     = $node->isFinal();

        return $ref;
    }

    /**
     * @param Interface_ $node
     *
     * @return RefClass
     */
    private function createInterface(Interface_ $node)
    {
        $ref = new RefClass();

        $ref->isInterface = true;
        $ref->fileName    = $this->file;
        $ref->name        = $this->getFullClassName((string)$node->name);
        $ref->docComment  = $this->createDocComment($node);

        $ref->startLine = $node->getLine();

        foreach ($node->extends as $implement) {
            $ref->implements[] = $this->getFullClassName($implement);
        }

        return $ref;
    }

    /**
     * @param Trait_ $node
     *
     * @return RefClass
     */
    private function createTrait(Trait_ $node)
    {
        $ref = new RefClass();

        $ref->isTrait    = true;
        $ref->fileName   = $this->file;
        $ref->name       = $this->getFullClassName((string)$node->name);
        $ref->docComment = $this->createDocComment($node);

        $ref->startLine = $node->getLine();

        return $ref;
    }

    /**
     * @param Name|string $name
     *
     * @return string
     */
    private function getFullClassName($name)
    {
        if ($name instanceof Name) {
            if ($name->isFullyQualified()) {
                return $name->toString();
            }
        }

        $name = (string)$name;

        if (isset($this->namespace->uses[$name])) {
            return $this->namespace->uses[$name];
        }

        if ($this->namespace->name) {
            return $this->namespace->name . '\\' . $name;
        }

        return $name;
    }

    /**
     * @param Name|string $name
     *
     * @return string|null
     */
    private function getTypeName($name)
    {
        if (!$name) {
            return null;
        }

        if ($name instanceof UnionType) {
            $types = [];
            foreach ($name->types as $type) {
                $types[] = $this->getTypeName($type);
            }

            return implode("|", $types);
        }

        if (is_string($name)) {
            return $name;
        }

        $prefix = '';
        if ($name instanceof NullableType) {
            $name   = $name->type;
            $prefix = '?';
        }

        return $prefix . $this->getFullClassName($name);
    }

    /**
     * @param Node $node
     *
     * @return RefComment|null
     */
    private function createDocComment(Node $node)
    {
        $docComment = $node->getDocComment();
        if (!$docComment) {
            return null;
        }

        $ref            = new RefComment();
        $ref->text      = $docComment->getText();
        $ref->startLine = $docComment->getLine();
        $ref->endLine   = $node->getLine() - 1;

        return $ref;
    }

    /**
     * @param Property $node
     *
     * @return RefProperty
     */
    private function createProperty(Property $node)
    {
        $ref = new RefProperty();

        $ref->class   = $this->class;
        $ref->name    = (string)$node->props[0]->name;
        $ref->default = $this->getValue($node->props[0]->default);

        if ($node->isPrivate()) {
            $ref->visibility = Visibility::IS_PRIVATE;
        } elseif ($node->isProtected()) {
            $ref->visibility = Visibility::IS_PROTECTED;
        } elseif ($node->isPublic()) {
            $ref->visibility = Visibility::IS_PUBLIC;
        }

        $ref->isStatic   = $node->isStatic();
        $ref->line       = $node->getLine();
        $ref->docComment = $this->createDocComment($node);

        return $ref;
    }

    /**
     * @param ClassConst $node
     *
     * @return RefConstant
     */
    private function createConstant(ClassConst $node)
    {
        $ref = new RefConstant();

        $ref->class      = $this->class;
        $ref->name       = (string)$node->consts[0]->name;
        $ref->value      = $this->getValue($node->consts[0]->value);
        $ref->line       = $node->getLine();
        $ref->docComment = $this->createDocComment($node);

        return $ref;
    }

    /**
     * @param ClassMethod $node
     *
     * @return RefMethod
     */
    private function createMethod(ClassMethod $node)
    {
        $ref        = new RefMethod();
        $ref->class = $this->class;
        $ref->name  = (string)$node->name;

        if ($node->isPrivate()) {
            $ref->visibility = Visibility::IS_PRIVATE;
        } elseif ($node->isProtected()) {
            $ref->visibility = Visibility::IS_PROTECTED;
        } elseif ($node->isPublic()) {
            $ref->visibility = Visibility::IS_PUBLIC;
        }

        $ref->isStatic   = $node->isStatic();
        $ref->isAbstract = $node->isAbstract();
        $ref->isFinal    = $node->isFinal();

        $ref->startLine  = $node->getLine();
        $ref->docComment = $this->createDocComment($node);

        $ref->returnType = $this->getTypeName($node->getReturnType());

        foreach ($node->getParams() as $param) {
            $param = $this->createParam($param, $ref->docComment);

            $ref->params[$param->name] = $param;
        }

        return $ref;
    }

    /**
     * @param Param      $param
     * @param RefComment $comment
     *
     * @return RefParam
     */
    private function createParam(Param $param, RefComment $comment = null)
    {
        $ref       = new RefParam();
        $ref->name = $param->var->name;

        if ($comment !== null) {
            $name = preg_quote($param->var->name, '/');
            if (preg_match('/@param\s+([^\s]+)\s+\$' . $name . '(?:\s*$|\s+(.*)$)/m', $comment->text, $matches)) {
                $ref->type        = $matches[1];
                $ref->description = isset($matches[2]) ? $matches[2] : null;
            }
        }

        if (!$ref->type) {
            $ref->type = $this->getTypeName($param->type);
        }

        $ref->byRef      = $param->byRef;
        $ref->isVariadic = $param->variadic;

        $ref->hasDefault = $param->default !== null;
        $ref->default    = $this->getValue($param->default);

        return $ref;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function getValue($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof String_ || $value instanceof LNumber || $value instanceof DNumber) {
            return $value->value;
        } elseif ($value instanceof ConstFetch) {
            $value = (string)$value->name;
            if ($value === 'null') {
                return null;
            } elseif ($value === 'true') {
                return true;
            } elseif ($value === 'false') {
                return false;
            }

            return $value;
        } elseif ($value instanceof Array_) {
            return $this->getArray($value);
        }

        return null;
    }

    /**
     * @param Array_ $array
     *
     * @return array
     */
    private function getArray(Array_ $array)
    {
        $return = [];

        foreach ($array->items as $item) {
            if ($item->key === null) {
                $return[] = $this->getValue($item->value);
            } else {
                $return[$this->getValue($item->key)] = $this->getValue($item->value);
            }
        }

        return $return;
    }
}
