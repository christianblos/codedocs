---
title: Configuration
taxonomy:
    category: docs
---

You have to configure CodeDocs by putting a **{@ConfigParam("configFile")}** file
into your project's root directory.

```yaml
{@FileContent("%configFile%")}
```

##### buildDir

The directory where all generated files will be placed.

##### processors

A list of _pre_ and _post_ processors (See [Processors](/usage/processors)).

##### sources

A list of sources for your documentation. A source can be configured by these properties:

| Name      | Description
| --------- | -----------
| baseDir   | If it starts with "./", it will be relative to the configuration file. Some [Markups](/usage/markups) will use this.
| docsDir   | Location of the markdown documentations (relative to baseDir if starting with "./").
| classDirs | A list of locations for your classes (relative to baseDir if starting with "./").

##### params

You can use them with annotations by surrounding the value with %-signs:

```md
@FileContent("%configFile%")
```

You can also use the [ConfigParam-Markup](/usage/markups/ConfigParam) to place these values
inside your documentation.
