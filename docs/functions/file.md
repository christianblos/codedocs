Returns the path either of the current file or of a class.

#### Parameters

| Name | Type   | Description
| ---- | ------ | ------------
| of   | string | (Optional) The class name

#### Example

```
{{ file() }}
{{ file(of:'SomeClass') }}
```