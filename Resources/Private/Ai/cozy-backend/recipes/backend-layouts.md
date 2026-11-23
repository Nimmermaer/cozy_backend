---
id: backend-layouts
title: Backend layouts in TSconfig, per user group
editor_need: "Editors should only see the content areas that exist on this kind of page."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Unchanged. Column 'identifier' is used by the page-content data processor (PAGEVIEW, v13+) as content.<identifier>.records."
examples:
  - Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts.tsconfig
  - Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts/Sidebar.tsconfig
  - Resources/Private/PageView/Pages/Default.html
anti_pattern: "Backend layout records in the database (not versionable); one layout with every column for everybody."
---
## Solution
```
mod.web_layout.BackendLayouts.sidebar {
  title = LLL:EXT:my_site/.../locallang.xlf:layout.sidebar
  config.backend_layout {
    colCount = 2
    rowCount = 1
    rows.1.columns {
      1 { name = Sidebar, colPos = 2, identifier = sidebar }
      2 { name = Main, colPos = 0, identifier = main }
    }
  }
}
[backend.user.isAdmin || 3 in backend.user.userGroupIds]
  # additional layouts for a group
[END]
```

## Pitfalls
- `colspan` per row must add up to `colCount`.
- Every used `colPos` must be part of `mod.SHARED.colPos_list` if that list is set.
- Hard-coded group uids differ between systems – document them or use site settings.

## References
- TSconfig Reference → Page TSconfig → mod.web_layout.BackendLayouts
