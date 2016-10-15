# Processors

Processors can be used to add additional steps to the execution of CodeDocs.
Just add them to the [configuration](configuration.md) if you need them.
You can also create your own Processors by implementing **\CodeDocs\ProcessorInterface**.

Here's a list of built-in processors:

{{parse(text:table(
    of: classes(
        implements: '\CodeDocs\ProcessorInterface',
        matches: '/CodeDocs\\Processor\\(?!Internal\\)/',
        notTaggedWith: 'defaultProcessor'
    ),
    cols: [
        'Processor'   => '{{ shortName(of: "%item%") }}',
        'Description' => '{{ docComment(of: "%item%", firstLine: true) }}',
    ]
))}}

---

Next: [State object](state.md)
