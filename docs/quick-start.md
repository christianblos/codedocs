# Quick Start

## Installation

Add CodeDocs to your project by using composer:

```
composer require codedocs/codedocs
```


## Configuration

By default, CodeDocs searches for a file named **codedocs.config.php**
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


## Create final documentation

Execute `vendor/bin/codedocs`.

Possibles Options:

| Option             | Description
| ------------------ | -----------
| -v, -vv, -vvv..    | Verbose output. The more **v**'s, the more output
| --no-color         | Supress colors in cli output
| --{name} ({value}) | Set/overwite config params


---

Next: [Configuration](configuration.md)
