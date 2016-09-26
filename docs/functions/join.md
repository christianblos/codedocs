Joins an array to one string.

#### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| values | string[] | The values to join
| using | string | (Optional) The separator

#### Example

```
{{ join(values:['one', 'two'], using:', ') }}
```