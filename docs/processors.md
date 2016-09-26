# Processors

Processors are executed one after another during the docs generation.
Each step is represented by one Processor.
The following processors are executed by CodeDocs:

| Processor | Description
| --------- | -----------
| MirrorDocs | Copies all docs to a temporary directory
| ParseAnnotations | Parses all annotations in the source code
| ParseDocs | Parses the documentation and replaces all Markups
| ParseSourceCode | Parses all classes, methods, properties and so on
| RunPostProcessors | Runs all configured Post-Processors
| RunPreProcessors | Runs all configured Pre-Processors

CodeDocs also provices additional processors you can use:

| Processor | Description
| --------- | -----------
| CopyExportFiles | Copies generated doc files to a given directory
| CreateFilesFromTopics | Creates files from Topic-Annotations
