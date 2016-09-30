# docComment()

Returns the doc comment of a class, method or class member.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The class name, method or class member
| excludeAnnotations | bool | (Optional) True to hide annotations
| firstLine | bool | (Optional) True to only return the first line

### Example

Source code:

```
<?php

class SomeClass
{
    /**
     * This method does something cool.
     *
     * Maybe there's a detailed description here.
     * But maybe not :)
     *
     * @param string $name
     */
    public function doSomething($name)
    {
    }
}
```

Documentation source:

```
{{ docComment(of:'SomeClass::doSomething', excludeAnnotations:true) }}
```

Result:

```
This method does something cool.

Maybe there's a detailed description here.
But maybe not :)
```

[See full example code here](../../examples/functions/docComment)