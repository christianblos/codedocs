# Quick Start

## Installation

Add CodeDocs to your project by using composer:

```
composer require {{ jsonValue(of:'composer.json', key:'name') }}
```


## Configuration

By default, CodeDocs searches for a file named **{{ defaultValue(of:'\CodeDocs\ConfigLoader::DEFAULT_FILE') }}**
in your project's root directory.

```php
{{ fileContent(of:'examples/codedocs.config.simple.php') }}
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
