---
id: backend-preview-override
title: Change how content elements look in the page module
editor_need: "The preview of an element in the page module is not helpful for editors."
mechanism: page-tsconfig
typo3: [13, 14]
version_notes: "Per-CType preview template via mod.web_layout.tt_content.preview.<CType> works in v13. Overriding backend templates via the 'templates' page TSconfig exists since v12; exact partial paths for previews are not verified for v14."
examples:
  - Configuration/Sets/CozyBackend/page.tsconfig
  - Resources/Private/TemplateOverrides/Partials/PageLayout/Record/text/Preview.html
anti_pattern: "Custom PreviewRenderer class when a Fluid template is enough."
---
## Solution
Preferred – one Fluid template per CType:
```
mod.web_layout.tt_content.preview.my_type = EXT:my_site/Resources/Private/Templates/Preview/MyType.html
```
Advanced – override core backend templates for a package:
```
templates.typo3/cms-backend.100 = my-vendor/my-site:Resources/Private/TemplateOverrides/
```

## Pitfalls
- Never ship `<f:debug>` in previews.
- Template overrides of core files break silently on updates – prefer the per-CType preview.

## References
- TSconfig Reference → Page TSconfig → mod.web_layout.tt_content.preview
