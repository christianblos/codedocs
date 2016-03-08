---
title: Processors
taxonomy:
    category: docs
---

Processors can be used to hook inside the documentation generation process.
They can be configured as **pre** or **post** processors.
**Each processor will run per source**.

| Type | Description
| ---- | -----
| Pre  | Will be executed before replacing markups.
| Post | Will be executed after replacing markups.

Here's a list of all possible processors:

{@Listing("processors", linked=true)}
