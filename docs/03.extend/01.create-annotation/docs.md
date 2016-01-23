---
title: Create an Annotation
taxonomy:
    category: docs
---

**Annotation** classes are simple classes with just public properties. CodeDocs is using the
[Doctrine Annotation Library](http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html)
to parse annotations. If you're not familiar with this library, it may help if you have a look into it first.

Your annotation must follow some rules so it can be recognized as an annotation:

- it must extend from **\CodeDocs\Annotation\Annotation**.
- it must have the **@Annotation** annotation.


## Namespaces

If your annotations are in your own namespace, you should also add it to the **{@ConfigParam("configFile")}**.
For each namespace you have to specify a path following the [PSR-0 Standard](http://www.php-fig.org/psr/psr-0/):
If the path starts with a dot (e.g. "./"), it will be relative to the {@ConfigParam("configFile")} file.

```yaml
annotationNamespaces:
  My\Annotation: ./annotations
```


## Example

Here's an example annotation:

```php
{@FileContent("examples/extend/annotation/annotations/My/Annotation/Example.php")}
```

You can use it with the full namespace...

```php
{@FileContent("examples/extend/annotation/classes/ExampleFullNamespace.php")}
```

... or with a use statement.

```php
{@FileContent("examples/extend/annotation/classes/ExampleImport.php")}
```

The string "this is a test" will be put inside the **$value** property.
**$something** will have the value "foo".

You can see that the CodeDocs-Parser can find this annotation by
having a look at the command output. Execute `{@ConfigParam("executable")} -vvv` and
see something like:

```
 ...
> extract annotations...
   > ...of class \ExampleFullNamespace
      > found My\Annotation\Example
   > ...of class \ExampleImport
      > found My\Annotation\Example
 ...
```
