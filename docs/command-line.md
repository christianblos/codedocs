# Command Line

Run CodeDocs by executing the command `vendor/bin/codedocs`.

## Config file

If your **codedocs.config.php** file is not located
in the root directory, you can pass it to the command as argument:

```bash
$ vendor/bin/codedocs /path/to/config
```

You can also use multiple config files:

```bash
$ vendor/bin/codedocs codedocs.config.php codedocs.config.local.php
```

## Options

#### Verbose

For more verbose output, add **-v**, **-vv** and so on (the more **v**'s the more output):

```bash
$ vendor/bin/codedocs -v
```


#### No Colors

To supress colors in cli output, use:

```bash
$ vendor/bin/codedocs --no-color
```


#### Params

It is also possible to set/overwite params:

```bash
$ vendor/bin/codedocs --foo bar --doSomething
```
