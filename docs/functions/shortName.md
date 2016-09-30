# shortName()

Returns the class name without namespace.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The class name

### Example

Source code:

```
<?php
namespace My\Name\Space;

class SomeClass
{
}
```

Documentation source:

```
{{ shortName(of:'\My\Name\Space\SomeClass') }}
```

Result:

```
SomeClass
```

[See full example code here](../../examples/functions/shortName)