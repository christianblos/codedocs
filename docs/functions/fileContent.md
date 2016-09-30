# fileContent()

Returns the content of a file.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The path to the file relative to the baseDir

### Example

Documentation source:

```
{{ fileContent(of: 'config.php') }}
```

Result:

```
<?php
/** @var \CodeDocs\Config $config */

$config->baseDir = __DIR__;

$config->buildDir = './build';

$config->docsDir = './docs-src';
```

[See full example code here](../../examples/functions/fileContent)