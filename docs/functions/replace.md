# replace()

Replaces strings in the given text.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| text | string | The text where to replace strings
| using | string[] | The replacements (Key = search, Value = replacement)

### Example

Documentation source:

```
{{ replace(text: 'This is a test', using: ['This is' => 'Just']) }}
```

Result:

```
Just a test
```

[See full example code here](../../examples/functions/replace)