# jsonValue()

Returns a value from a json file.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| of | string | The json file relative to the baseDir
| key | string | The key

### Example

test.json:

```
{
  "name": "My name",
  "desc": "My description"
}
```

Documentation source:

```
{{ jsonValue(of: 'files/test.json', key: 'desc') }}
```

Result:

```
My description
```

[See full example code here](../../examples/functions/jsonValue)