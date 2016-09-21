Renders a list of the given array

#### Parameters

| Name   | Type     | Description
| ------ | -------- | ------------
| of     | string[] | The list items
| prefix | string   | (Optional) The list prefix

#### Example

```
{{ list(of:classes(extends: 'SomeClass')) }}
{{ list(of:['A', 'B', 'C'], prefix:'# ') }}
```