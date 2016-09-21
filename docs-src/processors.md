# Processors

Processors are executed one after another during the docs generation.
Each step is represented by one Processor.
The following processors are executed by CodeDocs:

{{parse(text:table(
    of: classes(implements:'\CodeDocs\ProcessorInterface', list: tagged(by:'defaultProcessor')),
    cols: [
        'Processor'        => '{{ shortName(of: "__item__") }}',
        'Description' => '{{ docComment(of: "__item__", firstLine: true) }}',
    ]
))}}

CodeDocs also provices additional processors you can use:

{{parse(text:table(
    of: classes(implements:'\CodeDocs\ProcessorInterface', list: notTagged(by:'defaultProcessor')),
    cols: [
        'Processor'        => '{{ shortName(of: "__item__") }}',
        'Description' => '{{ docComment(of: "__item__", firstLine: true) }}',
    ]
))}}
