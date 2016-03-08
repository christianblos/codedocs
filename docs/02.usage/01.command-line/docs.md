---
title: Command Line
taxonomy:
    category: docs
---

Run CodeDocs by executing the command `{@ConfigParam("executable")}`.

##### Config file

If your **{@ConfigParam("configFile")}** file is not located in the root directory, you can pass it to the
command as argument:

```bash
$ {@ConfigParam("executable")} /path/to/{@ConfigParam("configFile")}
```

##### Options

For more verbose output, you can add **-v**, **-vv** or **-vvv**:

```bash
$ {@ConfigParam("executable")} -v
```

It is also possible to overwite params which you have defined in the [configuration](/usage/configuration).

```bash
$ {@ConfigParam("executable")} --someParam --otherParam=value
```
