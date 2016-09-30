# codeSnippet()

Returns a code snippet of a class, method or class member.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | A class, method, or a class member
| comment | bool | (Optional) True to also return the doc comment

### Example

Source code:

```
<?php

class SomeClass
{
    /**
     * @param string $name
     */
    public function sayHelloTo($name)
    {
        echo sprintf('Hello %s!', $name);
    }
}
```

Documentation source:

```
{{ codeSnippet(of:'SomeClass::sayHelloTo', comment:true) }}
```

Result:

```
/**
 * @param string $name
 */
public function sayHelloTo($name)
{
    echo sprintf('Hello %s!', $name);
}
```

[See full example code here](../../examples/functions/codeSnippet)