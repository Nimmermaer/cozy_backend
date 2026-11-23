---
id: fluid-components
title: Reusable markup with Fluid components and design tokens
editor_need: "Indirect: consistent output across elements without duplicating markup."
mechanism: fluid
typo3: [13, 14]
version_notes: "Components need Fluid >= 4.3. TYPO3 v13: require a matching typo3fluid/fluid version via Composer and register an own ComponentCollection (AbstractComponentCollection). TYPO3 v14.1 integrates Fluid components in the core (Feature #108508); v14.2 adds XSD autocompletion (Feature #109114)."
examples:
  - Classes/Component/ComponentCollection.php
  - Resources/Private/Components/Atom/Button/Button.html
  - Resources/Private/PageView/Pages/Default.html
  - Tokens/designTokens.json
anti_pattern: "Copy-pasted partials with dozens of arguments; custom ViewHelper classes for pure markup."
---
## Solution
- Component templates with `<f:argument>` and `<f:slot>`; one folder per component.
- Collection maps a namespace to the folder; additional variables (e.g. design tokens) via `getAdditionalVariables()`.
- Design tokens as JSON → SCSS (Style Dictionary) and → component variables, one source.

## Pitfalls
- Declare every argument with `<f:argument>`; avoid `additionalArgumentsAllowed()` returning true everywhere.
- Load token JSON once (cache in a property), not on every component render.
- Do not rely on `$GLOBALS['TYPO3_REQUEST']` being set (CLI, cache warmup).

## References
- https://docs.typo3.org/permalink/fluid:components
- https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.1/Feature-108508-FluidComponentsIntegration.html
