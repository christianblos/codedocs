# list()

Renders a list of the given array.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string[] | The list items
| prefix | string\|null | (Optional) The list prefix

### Example

Documentation source:

```
{{ list(of:['one', 'two', 'three']) }}

{{ list(of:['one', 'two', 'three'], prefix: '+ ') }}
```

Result:

```
- one
- two
- three

+ one
+ two
+ three
```

[See full example code here](../../examples/functions/list)