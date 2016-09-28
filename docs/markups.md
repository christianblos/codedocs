# Markups

Markups can be used inside the documentation files to acces the source code.
A markup consists of a function call inside double curly braces. 

```
{{ someFunction() }}
```

## Parameters

Unlike normal function calls, the parameters must be named:

```
{{ someFunction(someNumber: 5) }} // correct
{{ someFunction(5) }} // fails!
```

The following parameter types are supported:

- Number
- Boolean
- String
- Null
- Array (only short array syntax)
- Nested function call

## Return value

The return value of the markup function should be a **string**.
It replaces the markup in the generated documentation.

If the return value is an **array**, it is converted to a string containing one line per array value.

If the return value is an object of **\CodeDocs\Type\Parsable** it is parsed again so markups are replaced there as well.

## Functions

CodeDocs comes with some built-in markup functions by default.
You can also add your own functions in the configuration.

Here's a list of all default available functions:

| Name | Description
| ---- | -----------
| [classes()](functions/classes.md) | Returns a list of classes matching the given criteria.
| [codeSnippet()](functions/codeSnippet.md) | Returns a code snippet  of a class, method or class member.
| [constants()](functions/constants.md) | Returns a list of class constants.
| [defaultValue()](functions/defaultValue.md) | Returns the default value of a class member or method param.
| [docComment()](functions/docComment.md) | Returns the doc comment of a class, method or class member.
| [file()](functions/file.md) | Returns the path either of the current file or of a class.
| [fileContent()](functions/fileContent.md) | Returns the content of a file.
| [join()](functions/join.md) | Joins an array to one string.
| [jsonValue()](functions/jsonValue.md) | Returns a value from a json file.
| [methodParamsTable()](functions/methodParamsTable.md) | Creates a markdown table of method params.
| [notTagged()](functions/notTagged.md) | Returns a list of classes that are not tagged by the Tag-Annotation.
| [param()](functions/param.md) | Returns a param from the configuration.
| [parse()](functions/parse.md) | Parse a string to replace markups.
| [relpath()](functions/relpath.md) | Returns a path relative to the baseDir.
| [list()](functions/list.md) | Renders a list of the given array
| [replace()](functions/replace.md) | Replaces strings in the given text.
| [shortName()](functions/shortName.md) | Returns the class name without namespace.
| [table()](functions/table.md) | Creates a markdown table.
| [tagged()](functions/tagged.md) | Returns a list of classes tagged by the Tag-Annotation.
| [topic()](functions/topic.md) | Returns a topic created by the Topic-Annotation.


---

Next: [Annotations](annotations.md)
