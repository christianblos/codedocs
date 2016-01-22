---
title: Quick Start
taxonomy:
    category: docs
---

## Requirements

- PHP 5.4
- [Composer](https://getcomposer.org/)
- [PHP yaml extension](http://php.net/manual/en/book.yaml.php)


## Installation

Just run `composer require {@JsonValue(file="composer.json", key="name")}` to install
the latest version.


## Configuration

You have to configure CodeDocs by putting a **{@ConfigParam("configFile")}** file
into your project's root directory.

```yaml
{@FileContent("examples/basicConfig.yaml")}
```

See more about configurations [here](/usage/configuration).


## Run

Execute the command `{@ConfigParam("executable")}`.

If your **{@ConfigParam("configFile")}** file is not located in the root directory, you can pass it to the
command as first argument:

```bash
$ {@ConfigParam("executable")} /path/to/{@ConfigParam("configFile")}
```

For more verbose output, you can add **-v**, **-vv** or **-vvv**:

```bash
$ {@ConfigParam("executable")} -v
```
