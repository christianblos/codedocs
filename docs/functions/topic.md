# topic()

Returns a topic created by the Topic-Annotation.

### Parameters

| Name | Type | Description
| ---- | ---- | -----------
| id | string | The topic id

### Example

Source code:

```
<?php

class SomeClass
{
    /**
     * @CodeDocs\Topic(id="myTopic")
     *
     * This text belongs to the topic.
     * It ends until the next annotation starts.
     *
     * @CodeDocs\Topic(id="anotherTopic")
     *
     * This is another one...
     */
    public function __construct()
    {
    }
}
```

Documentation source:

```
{{ topic(id:'myTopic') }}
```

Result:

```
This text belongs to the topic.
It ends until the next annotation starts.
```

[See full example code here](../../examples/functions/topic)