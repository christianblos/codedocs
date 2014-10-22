## Predefined templates

### default

The `default` template allows the following *additional data* configuration in the *codedocs.json* file:

```json
{
    ...
    "additional": {
        "headerLinks": {
            "Some Label": "http://some-url"
        },
        "footerLinks": {
            "Info Text": null,
            "Some Label": "http://some-url"
        }
    }
}
```

Configuration | Description
------------- | -------------
headerLinks   | Links that will appear in the header of your documentation.
footerLinks   | Links that will appear in the footer of your documentation.
