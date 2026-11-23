---
id: layout-variants
title: Visual variants via the layout field instead of new content elements
editor_need: "Editors want the same element in a different look (e.g. highlighted, image left)."
mechanism: typoscript
typo3: [13, 14]
version_notes: "Unchanged. In v13+ prefer a 'layout' condition in the Fluid template for small differences; use CASE when whole templates differ."
examples:
  - Configuration/TsConfig/Page/TCEFORM.tsconfig
  - Configuration/TypoScript/Setup/CaseElement.typoscript
  - Resources/Private/Templates/Shorthand/Layout1.html
anti_pattern: "Copying an existing CType into a new one only to get another template."
---
## When
Data structure is identical, only the output differs.

## Solution
1. Offer the option: `TCEFORM.tt_content.layout.addItems.159 = Highlight` (label via XLF).
2. Render it, either in the Fluid template (`<f:if condition="{data.layout} == 159">`) or by switching templates:
```
temp.textmedia < tt_content.textmedia
tt_content.textmedia = CASE
tt_content.textmedia {
  key.field = layout
  default < temp.textmedia
  159 < temp.textmedia
  159.templateName = Shorthand/Layout1
}
```

## Pitfalls
- `CASE` replaces the element globally – keep `default` identical to the original.
- Keep CSS of a variant scoped to the element, not `body`/`*`.
- Use `frame_class` or `space_*_class` if the variant is only about spacing/frames.

## References
- TypoScript Reference → Content objects → CASE
