# Introduction

You can write technical documentations by creating **plain text files** (e.g. Markdown)
directly in your project's repository or even in the **Doc Comments** of your classes.

If you want to see an example, just have a look at this documentation.
It was also created by CodeDocs itself.


## How does it work?

CodeDocs parses your whole project and creates a list of your classes, methods, etc.
In your documentation, you can access the parsed information by using **Markups**.
If your code changes, your documentation will also change because of the reference to the code.

You can also use **Annotations** in your classes and let your documentation access them.


## Output

CodeDocs does **not** generate HTML-Code out of the box. It exports **plain text files (like Markdown)** to a configured directory.
You can then use a tool of your choice to create an HTML-Documentation out of it.
