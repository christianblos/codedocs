Returns a list of classes tagged by the Tag-Annotation.

#### Parameters

| Name     | Type     | Description
| -------- | -------- | ------------
| by       | string   | The tag name
| contents | bool     | (Optional) True to return annotation contents instead of class name or label
| classes  | string[] | (Optional) Use this list of classes instead of all parsed ones

#### Example

```
{{ tagged(by:'someTag') }}
```