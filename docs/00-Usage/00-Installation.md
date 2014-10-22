## Requirements

- PHP 5.4
- [Composer](https://getcomposer.org/) (optional but recommended)

## Install via Composer

Add the following dependency to your project's *composer.json* file:

```json
{
    "require": {
        "christianblos/codedocs": "1.*"
    }
}
```

## Configure

You need a config file named `codedocs.json` in order to generate your documentation.
A minimalistic configuration would look like:

```json
{
    "name": "Project Name",
    "description": "A short description of your project",
    "src": "docs",
    "dest": "build"
}
```

The location of the config file should be in the project root directory. But you can place it everywhere you like.

### Possible configurations

| Name        | Description
| ----------- | -----------
| name        | The name of your project
| description | A short description of your project
| src         | The directory of your *markdown* files [^0]
| dest        | The directory where to save the generated html files [^0]
| template    | The documentation template. Can be a predefined Template or a path to a template. Default is `default`. See [Available templates][tpl]
| additional  | Additional data for the template. See more in [Available templates][tpl]

[^0]: All configured paths must be relative to the config file.

[tpl]: ../Templates/Available-templates.html