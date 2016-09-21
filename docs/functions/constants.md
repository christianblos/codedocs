Returns a list of class constants.

#### Parameters

| Name    | Type   | Description
| ------- | -------| ------------
| of      | string | The class name
| matches | string | (Optional) Regex to filter constants

#### Example

```
{{ constants(of:'SomeClass') }}
{{ constants(of:'SomeClass', matches:'/^TYPE_/') }}
```