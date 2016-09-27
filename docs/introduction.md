# Introduction

You can write technical documentations by creating **plain text files** (e.g. Markdown)
directly in your project's repository or even in the **Doc Comments** of your classes.

If you want to see a live example, just have a look at this documentation.
It was also created by CodeDocs itself.


## How does it work?

CodeDocs parses your whole project and creates a list of your classes, methods, etc
(You can also use [Annotations](annotations.md) and let CodeDocs access them).

In your documentation, you can access the parsed information by using [Markups](markups.md).
If your code changes, your documentation will also change because of the reference to the code.

![How it works](img/how-it-works.png)

## Generate HTML?

CodeDocs does **not** generate HTML-Code out of the box.
It just exports **plain text files** (like Markdown).
But you can use a [Processor](processors.md) and a tool of your choice
(like [Grav](https://getgrav.org/) or [Jekyll](https://jekyllrb.com/)) to create HTML.

---

Next: [Quick Start](quick-start.md)
