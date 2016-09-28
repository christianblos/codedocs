# Processors

Processors are executed one after another during the docs generation.
You can add Processors in your [configuration](configuration.md) if you need additional
steps during the documentation generation.

It is possible to create your own processors by implementing \CodeDocs\ProcessorInterface.
But CodeDocs also provices built-in processors you can use:

{{parse(text:table(
    of: classes(
        implements: ['\CodeDocs\ProcessorInterface'],
        matches: '/CodeDocs\\Processor\\(?!Internal\\)/',
        list: notTagged(by:'defaultProcessor')
    ),
    cols: [
        'Processor'   => '{{ shortName(of: "__item__") }}',
        'Description' => '{{ docComment(of: "__item__", firstLine: true) }}',
    ]
))}}
