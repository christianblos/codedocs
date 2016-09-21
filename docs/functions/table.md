Creates a markdown table.

#### Parameters

| Name | Type     | Description
| ---- | -------- | ------------
| of   | string[] | The items (A row is created per item)
| cols | string[] | Key = column name, Value column content ('__item__' is replaced by the actual item value)

#### Example

```
| Name | Description
| ---- | -----------
| classes() | @CodeDocs\Topic(file="functions/classes.md")
| codeSnippet() | @CodeDocs\Topic(file="functions/codeSnippet.md")
| constants() | @CodeDocs\Topic(file="functions/constants.md")
| defaultValue() | @CodeDocs\Topic(file="functions/defaultValue.md")
| docComment() | @CodeDocs\Topic(file="functions/docComment.md")
| file() | @CodeDocs\Topic(file="functions/file.md")
| fileContent() | @CodeDocs\Topic(file="functions/fileContent.md")
| join() | @CodeDocs\Topic(file="functions/join.md")
| jsonValue() | @CodeDocs\Topic(file="functions/jsonValue.md")
| notTagged() | @CodeDocs\Topic(file="functions/notTagged.md")
| param() | @CodeDocs\Topic(file="functions/param.md")
| parse() | @CodeDocs\Topic(file="functions/parse.md")
| relpath() | @CodeDocs\Topic(file="functions/relpath.md")
| list() | @CodeDocs\Topic(file="functions/list.md")
| shortName() | @CodeDocs\Topic(file="functions/shortName.md")
| table() | @CodeDocs\Topic(file="functions/table.md")
| tagged() | @CodeDocs\Topic(file="functions/tagged.md")
| topic() | @CodeDocs\Topic(file="functions/topic.md")

```