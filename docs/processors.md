# Processors

Processors can be used to add additional steps to the execution of CodeDocs.
Just add them to the [configuration](configuration.md) if you need them.
You can also create your own Processors by implementing **\CodeDocs\ProcessorInterface**.

Here's a list of built-in processors:

| Processor | Description
| --------- | -----------
| CopyExportFiles | Copies generated doc files to a given directory
| CreateFilesFromTopics | Creates files from Topic-Annotations

---

Next: [State object](state.md)
