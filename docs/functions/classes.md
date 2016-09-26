Returns a list of classes matching the given criteria.

#### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| matches | string\|null | (Optional) Regex to match class name
| extends | string\|null | (Optional) Returns only classes extending this class
| implements | string[] | (Optional) Returns only classes implementing these interfaces
| list | string[]\|null | (Optional) Returns only classes in this list

#### Example

```
{{ classes(extends:'\CodeDocs\Doc\MarkupFunction') }}
```