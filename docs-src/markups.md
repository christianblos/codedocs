# Markups

Markups can be used inside the documentation files to acces the source code.
A markup consists of a function call inside double curly braces. 

```
\{{ someFunction() }}
```

## Parameters

Unlike normal function calls, the parameters must be named:

```
\{{ someFunction(someNumber: 5) }} // correct
\{{ someFunction(5) }} // fails!
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
You can also register your own functions in the configuration.

Here's a list of all default available functions:

{{parse(text:table(
    of: classes(extends:'\CodeDocs\Doc\MarkupFunction'),
    cols: [
        'Name'        => '[{{ defaultValue(of: "%item%::FUNC_NAME") }}()](functions/{{ defaultValue(of: "%item%::FUNC_NAME") }}.md)',
        'Description' => '{{ docComment(of: "%item%", firstLine: true, excludeAnnotations: true) }}',
    ]
))}}


---

Next: [Annotations](annotations.md)
