---
id: wizard-presets
title: Pre-configured elements in the new content element wizard
editor_need: "Editors should pick 'Text left, image right' instead of configuring textpic manually."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "v13.0: CTypes are registered in the wizard automatically from TCA (group, icon, description of the CType item). The TSconfig option 'wizardItems.<group>.show' is no longer evaluated (Breaking #102834) – use 'removeItems' to hide entries. v14: not verified for further changes."
examples:
  - Configuration/TsConfig/Page/Mod/Wizard/NewContentElementWizard/CozyBackend.tsconfig
  - Configuration/TCA/Overrides/tt_content.php
anti_pattern: "A new CType that only differs by default values."
---
## When
An existing element with specific default values is used often.

## Solution
```
mod.wizards.newContentElement.wizardItems.common {
  elements.text_left_image_right {
    iconIdentifier = content-beside-text-img-right
    title = LLL:EXT:my_site/Resources/Private/Language/locallang.xlf:wizard.textLeftImageRight
    description = LLL:EXT:my_site/Resources/Private/Language/locallang.xlf:wizard.textLeftImageRight.description
    tt_content_defValues {
      CType = textpic
      imageorient = 25
    }
  }
  removeItems := addToList(html)
}
```
For own CTypes: set `group`, `icon` and `description` on the TCA select item – no TSconfig needed.

## Pitfalls
- `show := addToList(...)` does nothing since v13 – use `removeItems`.
- A FlexForm default in `tt_content_defValues.pi_flexform` must be valid XML; omit the `<?xml ?>` declaration in indented multiline values.

## References
- https://docs.typo3.org/permalink/t3coreapi:content-element-wizard
- https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102834-RemoveItemsFromNewContentElementWizard.html
