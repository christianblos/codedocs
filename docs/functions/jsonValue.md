Returns a value from a json file.

#### Parameters

| Name | Type   | Description
| ---- | ------ | ------------
| of   | string | The json file relative to the baseDir
| key  | string | The key

#### Example

```
{{ jsonValue(of:'composer.json', key:'name') }}
```