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

$config->buildDir = './build';

$config->docsDir = './docs-src';

$config->classDirs = ['src'];

$config->cacheDir = '/tmp/codedocs';
```

See more about configurations [here](configuration.md).


## Run

Execute `vendor/bin/codedocs`.
