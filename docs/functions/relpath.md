# relpath()

Returns a path relative to the baseDir.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The absolute path
| separator | string | (Optional) The separator

### Example

Documentation source:

```
{{ relpath(of: file()) }}
```

Result:

```
build/export/doc.md
```

[See full example code here](../../examples/functions/relpath)