Returns a list of classes that are not tagged by the Tag-Annotation.

#### Parameters

| Name     | Type     | Description
| -------- | -------- | ------------
| by       | string   | The tag name
| classes  | string[] | (Optional) Use this list of classes instead of all parsed ones

#### Example

```
{{ notTagged(by:'someTag') }}
```