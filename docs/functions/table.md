# table()

Creates a markdown table.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string[] | The items (A row is created per item)
| cols | string[] | Key = column name, Value column content ('__item__' is replaced by the actual item value)

### Example

Documentation source:

```
{{parse(text: table(
    of: ['Number 1', 'Number 2', 'Number 3'],
    cols: [
        'Nr.'    => '{{ replace(text: "__item__", using: ["Number " => ""]) }}',
        'Value ' => '__item__'
    ]
))}}
```

Result:

```
| Nr. | Value 
| --- | ------
| 1 | Number 1
| 2 | Number 2
| 3 | Number 3
```

[See full example code here](../../examples/functions/table)