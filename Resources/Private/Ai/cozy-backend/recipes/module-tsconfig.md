---
id: module-tsconfig
title: Info module, recycler and list module options
editor_need: "Editors need an overview of fields across pages, a usable recycler, and no access to technical record types."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Unchanged in principle; verify option names against the TSconfig reference of your version."
examples:
  - Configuration/TsConfig/Page/Mod/WebInfo/Configuration.tsconfig
  - Configuration/TsConfig/Page/Mod/Recycler/Configuration.tsconfig
  - Configuration/TsConfig/Page/Mod/WebList/Configuration.tsconfig
anti_pattern: "Custom backend module for a simple field overview."
---
## Solution
```
mod.web_info.fieldDefinitions.seo {
  label = SEO overview
  fields = title, seo_title, description
}
mod.recycler.allowDelete = 1
mod.web_list.deniedNewTables = fe_groups, fe_users
```

## Pitfalls
- `deniedNewTables` only hides the "new" action, it is not a permission.

## References
- TSconfig Reference → Page TSconfig → mod
