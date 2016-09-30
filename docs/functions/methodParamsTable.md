# methodParamsTable()

Creates a markdown table of method params.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The method

### Example

Source code:

```
<?php

class SomeClass
{
    /**
     * Logs something
     *
     * @param string $action The action to log
     * @param int    $time   Current timestamp
     */
    public function log($action, $time = null)
    {
    }
}
```

Documentation source:

```
{{ methodParamsTable(of:'\SomeClass::log') }}
```

Result:

```
| Name | Type | Description
| ---- | ---- | -----------
| action | string | The action to log
| time | int | (Optional) Current timestamp
```

[See full example code here](../../examples/functions/methodParamsTable)