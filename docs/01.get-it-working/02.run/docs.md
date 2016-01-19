---
title: Run
taxonomy:
    category: docs
---

Execute the command `{@ConfigParam("executable")}`.

If your **{@ConfigParam("configFile")}** file is not located in the root directory, you can pass it to the
command as first argument:

```bash
$ {@ConfigParam("executable")} /path/to/{@ConfigParam("configFile")}
```

For more verbose output, you can add **-v**:

```bash
$ {@ConfigParam("executable")} -v
```
