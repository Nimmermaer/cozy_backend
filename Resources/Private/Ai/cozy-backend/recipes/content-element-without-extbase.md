---
id: content-element-without-extbase
title: Lean content element without Extbase (TCA + TypoScript + Fluid)
editor_need: "Editors need an element with its own fields that no existing element covers."
mechanism: tca-override
typo3: [13, 14]
version_notes: "v13: register with ExtensionManagementUtility::addTcaSelectItem (group, icon, description → wizard). Plugins as CType via addPlugin(..., 'CType'). v14: addPiFlexFormValue() is deprecated (#107047) – assign the FlexForm via the plugin registration or columnsOverrides; FlexFormService was merged into FlexFormTools (Breaking #107945)."
examples:
  - Configuration/TCA/Overrides/tt_content.php
  - Configuration/TypoScript/Setup/Contentelements.typoscript
  - Resources/Private/Templates/DoubleText.html
  - Classes/UserFunc/ElementListUserFunc.php
anti_pattern: "Extbase plugin with controller, repository and domain model just to output a few fields."
---
## When
Only after `layout-variants`, `wizard-presets` and `columns-overrides` don't fit.

## Solution
1. TCA: add CType item (with `group`, `icon`, `description`) and `types.<CType>.showitem` reusing core palettes.
2. TypoScript: `tt_content.my_type =< lib.contentElement` + `templateName = MyType`.
3. Fluid: template using `{data.*}`; add `dataProcessing` (files, menu, database-query) for related data.
4. Need PHP logic? Pass a `USER` object as a variable of the FLUIDTEMPLATE (`variables.list = USER`)
   or write a DataProcessor – numeric sub-objects like `20 = USER` are ignored by FLUIDTEMPLATE.

## Pitfalls
- Query with `FrontendRestrictionContainer` (hidden, start/end time, fe_group, workspaces).
- FlexForm values with dotted keys (`settings.sorting`) arrive nested: `['settings']['sorting']`.
- Reuse core palettes (`general`, `headers`, `frames`, `language`, `access`) so the form looks familiar.

## References
- TYPO3 Explained → Content elements → Create a custom content element type
- https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107047-ExtensionManagementUtilityAddPiFlexFormValue.html
