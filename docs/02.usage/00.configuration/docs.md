---
title: Configuration
taxonomy:
    category: docs
---

CodeDocs can be configured by creating a **yaml**-File ({@ConfigParam("configFile")}) in your
project's root directory. Here's a full configuration sample:

```yaml
{@FileContent("examples/fullConfig.yaml")}
```

##### buildDir

The directory where all generated files will be placed.
If it starts with a dot (like "./"), it will be relative to the configuration file.
The default value is **./{@ClassValue("\CodeDocs\Component\ConfigReader::DEFAULT_BUILD_DIR")}**.


##### processors

A list of _pre_ and _post_ processors (See [Processors](/usage/processors)).
You can pass params to a processor like in the example above.


##### sources

A list of sources for your documentation. A source can be configured by these properties:

| Name      | Description
| --------- | -----------
| baseDir   | If it starts with "./", it will be relative to the configuration file. Some [Markups](/usage/markups) will also use this value.
| docsDir   | Location of the markdown documentations (relative to baseDir if starting with "./").
| classDirs | A list of locations for your classes (relative to baseDir if starting with "./").


##### params

You can use them with annotations by surrounding the value with %-signs:

```md
@FileContent("%configFile%")
```

You can also use the [ConfigParam-Markup](/usage/markups/ConfigParam) to place these values
inside your documentation.


##### annotationNamespaces

If you want to use your own annotations, you have to register namespaces here.
See [Create an Annotation](/extend/create-annotation) for more information.


##### markupNamespaces

Namespaces for markups can be registered here so you don't have to write the full namespace for your
own Markup classes. See [Create a Markup](/extend/create-markup) for more information.


##### plugins

If you want to use plugins, you must add them here.
You can also pass params to a plugin like in the example above.
