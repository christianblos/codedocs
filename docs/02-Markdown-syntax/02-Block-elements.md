## Blockquotes
> This is a blockquote. It starts with a `>` symbol.


## Tables

You can create tables by using hyphens (`-`) and pipes (`|`).
You can also use colons (`:`) to define the alignments.

**Markdown text:**

```markdown
| Left aligned | Center aligned | Right aligned |
| :----------- | :------------: | ------------: |
| **First**    | One            | 1             |
| **Second**   | Two            | 22            |
| **Third**    | Three          | 333           |
```

**Result:**

| Left aligned | Center aligned | Right aligned |
| :----------- | :------------: | ------------: |
| **First**    | One            | 1             |
| **Second**   | Two            | 22            |
| **Third**    | Three          | 333           |

## Horizontal rules 

You can create a horizontal rule (`<hr>`) by writing three or more hyphens(`-`), asterisks (`*`) or underscores (`_`)
in one line. It is also possible to use spaces between them.

----------


## Code blocks

Use triple backticks (\`\`\`) to define code blocks:

```markdown
This is a code block
with multiple lines.
```

If you add the name of the code language after the first three backticks, it will be highlighted:

```php
if ($lineStartsWith == '```php') {
    echo 'This is php code';
}
```