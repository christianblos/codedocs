---
title: Create a Plugin
taxonomy:
    category: docs
---

With a **Plugin** it is possible to make configurations without touching the {@ConfigParam("configFile")} file.
You can create a plugin by providing a class which extends from **\CodeDocs\Component\Plugin**.


## Example

Here's an example Plugin class:

```php
{@FileContent("examples/extend/plugin/classes/example.php")}
```

With the **$config** object in the **mount()** method you can set all configurations which are
also configurable in the {@ConfigParam("configFile")} file.

The plugin can be registered in the {@ConfigParam("configFile")}:

```yaml
{@FileContent("examples/extend/plugin/config.yaml")}
```

In this example, you can see that a param called **foo** will be passed to the plugin.
In the Plugin class it is then passed to the configuration to make it available in the
documentation:

```md
{@FileContent("examples/extend/plugin/docs/example.md")}
```

The result will look like this:

```md
{@FileContent("examples/extend/plugin/export/example.md")}
```
