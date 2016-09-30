# param()

Returns a param from the configuration.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The name of the param
| default | mixed | (Optional) The default value if param is not set

### Example

params.php:

```
<?php
// This return value is used to fill $config->params
return [
    'myParam' => 'myValue',
];
```

Documentation source:

```
{{ param(of:'myParam') }}

{{ param(of:'anotherParam', default: 'Some default') }}
```

Result:

```
myValue

Some default
```

[See full example code here](../../examples/functions/param)