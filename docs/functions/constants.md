# constants()

Returns a list of class constants.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The class name
| matches | string\|null | (Optional) Regex to filter constants

### Example

Source code:

```
<?php

class SomeClass
{
    const NO_TYPE    = 0;
    const TYPE_ONE   = 1;
    const TYPE_TWO   = 2;
    const TYPE_THREE = 3;
}
```

Documentation source:

```
{{ constants(of:'SomeClass', matches:'/^TYPE_/') }}
```

Result:

```
TYPE_ONE
TYPE_TWO
TYPE_THREE
```

[See full example code here](../../examples/functions/constants)