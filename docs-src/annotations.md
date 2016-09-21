# Annotations

Annotations can be used in classes to help generating the documentation.
The Markups in the documentaion files are able to access all these annotations.

CodeDocs uses the [Doctrine Annotations](https://github.com/doctrine/annotations) library.
You can also use your own Annotations if you want.

Here's a list of all build-in annotations provided by CodeDocs:

{{parse(text:table(
    of: classes(extends:'\CodeDocs\Annotation'),
    cols: [
        'Name'        => '__item__',
        'Description' => '{{ docComment(of: "__item__", firstLine: true) }}',
    ]
))}}
