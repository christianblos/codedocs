Returns a path relative to the baseDir.

#### Parameters

| Name      | Type   | Description
| --------- | ------ | ------------
| of        | string | The absolute path
| separator | string | (Optional) The separator

#### Example

```
{{ relpath(of:file(of:'SomeClass')) }}
```