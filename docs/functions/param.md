Returns a param from the configuration.

#### Parameters

| Name    | Type   | Description
| ------- | ------ | ------------
| of      | string | The name of the param
| default | mixed  | The default value if param is not set

#### Example

```
{{ param(of:'appName') }}
{{ param(of:'env', default:'development') }}
```