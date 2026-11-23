---
id: tceform-hide-rename
title: Hide, rename and reduce fields and options
editor_need: "Editors see too many or unclear fields and options."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Unchanged in principle in v13/v14. For 'removeItems' on CType prefer ':= addToList()' so other sets can extend it."
examples:
  - Configuration/TsConfig/Page/TCEFORM.tsconfig
  - Resources/Private/Language/locallang.xlf
anti_pattern: "New content element or FormEngine XCLASS just to hide fields or rename labels; changing TCA globally for one site."
---
## When
Fields are irrelevant for editors, labels are too technical, select boxes offer too many options.

## Solution
```
TCEFORM.tt_content {
  date.disabled = 1
  subheader.label = LLL:EXT:my_site/Resources/Private/Language/locallang.xlf:subheader
  subheader.types.textmedia.label = Teaser line
  layout {
    removeItems = 3
    altLabels.1 = Boxed
    addItems.159 = Highlight
  }
  CType.removeItems := addToList(div, html)
}
TCEFORM.pages.layout.disabled = 1
```
`types.<CType>` limits a setting to one content type.

## Pitfalls
- `disabled` only hides the field in the form; existing values remain in the database.
- Use XLF labels (`LLL:`) instead of hard-coded text so translations work.
- `addItems` only adds the option – the frontend must render it (see `layout-variants`).

## References
- TSconfig Reference → Page TSconfig → TCEFORM
