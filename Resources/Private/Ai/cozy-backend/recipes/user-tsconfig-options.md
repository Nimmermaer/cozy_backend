---
id: user-tsconfig-options
title: Page tree and module options per user (User TSconfig)
editor_need: "Editors lose orientation in the page tree or see modules they never use."
mechanism: user-tsconfig
typo3: [13, 14]
version_notes: "Unchanged in principle. Extension-wide user TSconfig lives in Configuration/user.tsconfig (v12+). Module identifiers changed over versions – verify names in the System > Configuration module."
examples:
  - Configuration/user.tsconfig
  - Configuration/TsConfig/User/options.tsconfig
anti_pattern: "Granting/denying modules only via hard-coded group records without documentation; custom JS in the page tree."
---
## Solution
```
options {
  pageTree.showPageIdWithTitle = 1
  pageTree.backgroundColor.42 = #e8f4f8
  hideModules := addToList(system_reactions)
}
```
Prefer access rights on backend groups for security; `hideModules` is a UX convenience.

## Pitfalls
- `hideModules` does not replace permissions.
- Page uids in `backgroundColor` are system-specific.

## References
- TSconfig Reference → User TSconfig → options
