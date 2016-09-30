# file()

Returns the path either of the current file or of a class.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string\|null | (Optional) The class name

### Example

Documentation source:

```
{{ relpath(of: file()) }}

{{ relpath(of: file(of:'MyClass')) }}
```

Result:

```
build/export/doc.md

code/MyClass.php
```

[See full example code here](../../examples/functions/file)