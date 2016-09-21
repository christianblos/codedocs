Creates a markdown table.

#### Parameters

| Name | Type     | Description
| ---- | -------- | ------------
| of   | string[] | The items (A row is created per item)
| cols | string[] | Key = column name, Value column content ('\_\_item__' is replaced by the actual item value)

#### Example

```
{{parse(text:table(
  of: classes(extends:'\CodeDocs\Doc\MarkupFunction'),
  cols: [
    'Name'        => '{{ defaultValue(of:"__item__::FUNC_NAME") }}()',
    'Description' => '{{ docComment(of:"__item__", firstLine: true) }}',
  ]
))}}
```
