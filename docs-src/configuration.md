# Configuration

By default, CodeDocs searches for a file named **{{ defaultValue(of:'\CodeDocs\ConfigLoader::DEFAULT_FILE') }}**
in your project's root directory. But you can use another location if you want.

Here's a full configuration sample with descriptions:

```php
{{ parse(text:fileContent(of:'examples/codedocs.config.full.php')) }}
```
