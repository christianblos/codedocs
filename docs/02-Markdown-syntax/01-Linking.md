## Links

There are multiple ways to create links. Here are some examples:

**Markdown text:**

```markdown
- http://github.com
- [Github](http://github.com)
- [Github (with title)](http://github.com "This is a title")
- [Parsedown][pd]
- [Google][]
- [Link to an element id](#some-id)

[pd]: http://parsedown.org/ "Markdown parser written in PHP"
[Google]: http://google.com
```

**Result:**

- http://github.com
- [Github](http://github.com)
- [Github (with title)](http://github.com "This is a title")
- [Parsedown][pd]
- [Google][]
- [Link to an element ID](#some-id)

[pd]: http://parsedown.org/ "Markdown parser written in PHP"
[Google]: http://google.com


## Images

Images can be included in a way similar to the links:

**Markdown text:**

```markdown
![alt text](http://placehold.it/100x50)
![alt text](http://placehold.it/150x50/49bbe5/fff&text=with+title "This is a title")
![alt text][img]

[img]: http://placehold.it/120x50/1fad47/fff&text=reference "This is an optional title"
```

**Result:**

![alt text](http://placehold.it/100x50)
![alt text](http://placehold.it/150x50/49bbe5/fff&text=with+title "This is a title")
![alt text][img]

[img]: http://placehold.it/120x50/1fad47/fff&text=reference "This is an optional title"


## Abbreviations

To create abbreviations you can just add definitions like the following to the document:

```markdown
*[abbreviations]: This is an example.
```

*[abbreviations]: This is an example.


## Footnotes

Link to footnotes by creating a marker [^0] like `[^1]`. The footnote definitions [^1] are placed
at the end of the document.

[^0]: The *markers* must start with ^0.
[^1]: The *definitions* are placed at the end of the document.
