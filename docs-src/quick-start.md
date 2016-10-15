# Quick Start


### 1. Install CodeDocs

Add CodeDocs to your project by using composer:

```
composer require {{ jsonValue(of:'composer.json', key:'name') }}
```


### 2. Create Configuration

Create a file named **{{ defaultValue(of:'\CodeDocs\ConfigLoader::DEFAULT_FILE') }}**
in your project's root directory.

```php
{{ fileContent(of:'examples/codedocs.config.simple.php') }}
```

See more about configurations [here](configuration.md).


### 3. Create the documentation source

Create a folder (e.g. **docs-src**) with your documentation.
It can be Markdown, RST, HTML or whatever you want.
[See how to use Markups here](markups.md)


### 4. Export final documentation

Execute `vendor/bin/codedocs`.

Possibles command line options:

| Option             | Description
| ------------------ | -----------
| -v, -vv, -vvv..    | Verbose output. The more **v**'s, the more output
| --no-color         | Supress colors in cli output
| --{name} ({value}) | Set/overwite config params


---

Next: [Configuration](configuration.md)
