Returns a code snippet  of a class, method or class member.

#### Parameters

| Name    | Type   | Description
| ------- | -------| ------------
| of      | string | A class, method, or a class member
| comment | bool   | (Optional) True to also return the doc comment

#### Example

```
{{ codeSnippet(of:'SomeClass') }}
{{ codeSnippet(of:'SomeClass::someMethod') }}
```