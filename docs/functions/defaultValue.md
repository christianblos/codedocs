Returns the default value of a class member or method param.

#### Parameters

| Name | Type   | Description
| ---- | -------| ------------
| of   | string | The reference to a class member or method param

#### Example

```
{{ defaultValue(of:'SomeClass::SOME_CONST') }}
{{ defaultValue(of:'SomeClass::$someProperty') }}
{{ defaultValue(of:'SomeClass::someMethod(someParam)') }}
```