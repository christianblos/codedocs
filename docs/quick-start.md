# Quick Start


### 1. Install CodeDocs

Add CodeDocs to your project by using composer:

```
composer require codedocs/codedocs
```


### 2. Create Configuration

Create a file named **codedocs.config.php**
in your project's root directory.

```php
<?php
/** @var \CodeDocs\Config $config */

$config->baseDir = __DIR__;

// All generated files are stored here
$config->buildDir = './build';

// Your documentation source with markups
$config->docsDir = './docs-src';

// Your source code to parse
$config->classDirs = ['./src'];
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
