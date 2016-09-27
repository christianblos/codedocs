# Processors

Processors are executed one after another during the docs generation.
You can add Processors in your [configuration](configuration.md) if you need additional
steps during the documentation generation.

It is possible to create your own processors by implementing \CodeDocs\ProcessorInterface.
But CodeDocs also provices built-in processors you can use:

| Processor | Description
| --------- | -----------
| CopyExportFiles | Copies generated doc files to a given directory
| CreateFilesFromTopics | Creates files from Topic-Annotations
