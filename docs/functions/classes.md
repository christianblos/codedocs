# classes()

Returns a list of classes matching the given criteria.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| matches | string\|null | (Optional) Regex to match class name
| extends | string\|null | (Optional) Returns only classes extending this class
| implements | string[] | (Optional) Returns only classes implementing these interfaces
| taggedWith | string[] | (Optional) Returns only classes with these Tag annotations
| notTaggedWith | string[] | (Optional) Returns only classes without these Tag annotations

### Example

Documentation source:

```
{{ classes(extends:'\ParentClass') }}

{{ classes(implements:['\SomeInterface'], matches:'/^Class[A-Z]/') }}
```

Result:

```
ChildClass

ClassA
ClassB
```

[See full example code here](../../examples/functions/classes)