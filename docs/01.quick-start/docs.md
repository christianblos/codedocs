---
title: Quick Start
taxonomy:
    category: docs
---

## Installation

Just run `composer require {@JsonValue(file="composer.json", key="name")}` to install
the latest version.


## Configuration

You have to configure CodeDocs by putting a **{@ConfigParam("configFile")}** file
into your project's root directory.

```yaml
{@FileContent("examples/basicConfig.yaml")}
```

See more about configurations [here](/usage/configuration).


## Run

Execute the command `{@ConfigParam("executable")}`.
