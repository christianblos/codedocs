Returns a list of classes matching the given criteria.

#### Parameters

| Name       | Type     | Description
| ---------- | -------- | ------------
| matches    | string   | (Optional) Regex to match class name
| extends    | string   | (Optional) Returns only classes extending this class
| implements | string[] | (Optional) Returns only classes implementing these interfaces
| list       | string[] | (Optional) Returns only classes in this list

#### Example

```
{{ classes(extends:'\CodeDocs\Doc\MarkupFunction') }}
```