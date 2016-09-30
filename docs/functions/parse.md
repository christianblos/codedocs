# parse()

Parse a string to replace markups.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| text | string | The text to parse

### Example

included.md:

```
{{ list(of: ['A', 'B', 'C']) }}
```

Documentation source:

```
{{ fileContent(of: 'files/included.md') }}

{{ parse(text: fileContent(of: 'files/included.md')) }}
```

Result:

```
{{ list(of: ['A', 'B', 'C']) }}

- A
- B
- C
```

[See full example code here](../../examples/functions/parse)