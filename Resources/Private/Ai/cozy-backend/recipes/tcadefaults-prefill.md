---
id: tcadefaults-prefill
title: Pre-fill fields for new records
editor_need: "Editors keep typing the same values (author, layout, header) into new records."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Unchanged in v13/v14. Site set settings can be referenced as {$setting.name} in page TSconfig of a site set (v13+)."
examples:
  - Configuration/TsConfig/Page/TCAdefaults.tsconfig
  - Configuration/Sets/CozyBackend/settings.definitions.yaml
anti_pattern: "DataHandler hooks or PHP code that writes default values; editing TCA 'default' globally for one project need."
---
## When
New records should start with sensible values, possibly different per page tree or site.

## Solution
```
TCAdefaults {
  tt_content {
    header = {$tt_content.default.header}
    layout = {$tt_content.default.layout}
  }
  pages.author = Editorial team
}
```
Put it into the site set's `page.tsconfig`; define the `{$...}` values in `settings.definitions.yaml`
so integrators can change them in the site settings module.

## Pitfalls
- Only applies to **new** records, not existing ones.
- Values set via the new content element wizard (`tt_content_defValues`) win over TCAdefaults.
- The value must be valid for the field (e.g. an existing `layout` item).

## References
- TSconfig Reference → Page TSconfig → TCAdefaults
