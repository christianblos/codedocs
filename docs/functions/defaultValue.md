# defaultValue()

Returns the default value of a class member or method param.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The reference to a class member or method param

### Example

Source code:

```
<?php

class SomeClass
{
    const SOME_CONST = 1;

    private $someBool = false;

    public function join($values, $separator = ', ')
    {

    }
}
```

Documentation source:

```
{{ defaultValue(of:'\SomeClass::SOME_CONST') }}

{{ defaultValue(of:'\SomeClass::$someBool') }}

{{ defaultValue(of:'\SomeClass::join(separator)') }}
```

Result:

```
1

false

,
```

[See full example code here](../../examples/functions/defaultValue)