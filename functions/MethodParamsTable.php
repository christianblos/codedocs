<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func\Helper\MarkdownTrait;
use CodeDocs\Func\Helper\RefTrait;
use CodeDocs\SourceCode\Ref\RefMethod;

/**
 * @CodeDocs\Topic(file="functions/methodParamsTable.md")
 *
 * Creates a markdown table of method params.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\MethodParamsTable::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ methodParamsTable(of: '\CodeDocs\Func\MethodParamsTable::__invoke') }}
 * ```
 */
class MethodParamsTable extends MarkupFunction
{
    use RefTrait;
    use MarkdownTrait;

    const FUNC_NAME = 'methodParamsTable';

    /**
     * @param string $of The method
     *
     * @return string
     */
    public function __invoke($of)
    {
        $ref = $this->getRef($this->state, $of);

        if (!$ref instanceof RefMethod) {
            throw new MarkupException(sprintf('%s is not a method'));
        }

        $headlines = [
            'Name',
            'Type',
            'Description',
        ];

        $rows = [];

        foreach ($ref->params as $param) {
            $desc = $param->description;

            if ($param->hasDefault) {
                $desc = '(Optional) ' . $desc;
            }

            $rows[] = [
                $param->name,
                $param->type,
                $desc,
            ];
        }

        return $this->renderMarkdownTable($headlines, $rows);
    }
}
