Parse a string to replace markups.

#### Parameters

| Name | Type   | Description
| ---- | ------ | ------------
| text | string | The text to parse

#### Example

```
{{ parse(text:fileContent(of:'someFile')) }}
```