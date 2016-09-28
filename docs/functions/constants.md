# constants()

Returns a list of class constants.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The class name
| matches | string\|null | (Optional) Regex to filter constants

### Example

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