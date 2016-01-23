---
title: Create a Markup
taxonomy:
    category: docs
---

**Markups** are nearly the same as **Annotations** but must be enclosed into braces.
A Markup class must extend **\CodeDocs\Markup\Markup**.

A Markup object will be created for each markup string in the documentation.
The magic happens in the **buildContent** method of the Markup class.

```php
{@CodeSnippet("\CodeDocs\Markup\Markup::buildContent", comment=true)}
```

This method receives some parameters:

| Parameter    | Description
| ------------ | -----------
| $parseResult | Contains parsed annotations and classes
| $config      | Contains configurations
| $source      | Current running source

This method should return the string that will replace the markup string.
If you want this string to be parsed for markups as well, you have to return a
**Parsable**-Object (`return new Parsable($string)`).


## Namespaces

If your Markup classes are in your own namespace, you can just use it like:

```md
{@FileContent("examples/extend/markup/docs/namespace.md")}
```

But you can also add your namespace(s) to the {@ConfigParam("configFile")}:

```yaml
markupNamespaces:
  - MyNamespace
```

Now it is possible to use the simple class name:

```md
{@FileContent("examples/extend/markup/docs/simple.md")}
```


## Example

In this example we create a Markup which returns the current date.
The format can be specified with the first parameter.

```php
{@FileContent("examples/extend/markup/classes/example.php")}
```

Note that we added **@Annotation** to the doc comment. We need this because we're using
the [Doctrine Annotations Library](http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html)
to parse the markup.

The first parameter in the markup will be saved in the **$value** property.

Now we create a markdown file using this markup:

```md
{@FileContent("examples/extend/markup/docs/namespace.md")}
```

The result will looke like:

```md
{@FileContent("examples/extend/markup/export/namespace.md")}
```
