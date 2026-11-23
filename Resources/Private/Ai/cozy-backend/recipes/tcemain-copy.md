---
id: tcemain-copy
title: Copy records without "(copy)" suffix and without hiding
editor_need: "Copied pages/elements get '(copy 1)' and are hidden – editors have to clean up."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Unchanged."
examples:
  - Configuration/TsConfig/Page/TCEMAIN.tsconfig
anti_pattern: "DataHandler hook that renames copied records."
---
## Solution
```
TCEMAIN.table.pages {
  disablePrependAtCopy = 1
  disableHideAtCopy = 1
}
TCEMAIN.table.tt_content {
  disablePrependAtCopy = 1
  disableHideAtCopy = 1
}
TCEMAIN.translateToMessage = Please translate to "%s"
```

## Pitfalls
- Copied pages become visible immediately – decide consciously per table.

## References
- TSconfig Reference → Page TSconfig → TCEMAIN
