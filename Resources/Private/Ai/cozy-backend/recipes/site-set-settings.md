---
id: site-set-settings
title: Central, typed defaults via Site Set settings
editor_need: "Integrators should change defaults (texts, layouts, paths) per site without touching code."
mechanism: site-set
typo3: [13, 14]
version_notes: "Site sets and settings.definitions.yaml exist since v13.1; settings can be used in TypoScript and page TSconfig of the set as {$name}. A site uses a set via 'dependencies' in its config.yaml. v14 adds further settings editor improvements (not verified in detail)."
examples:
  - Configuration/Sets/CozyBackend/config.yaml
  - Configuration/Sets/CozyBackend/settings.definitions.yaml
  - Configuration/Sets/CozyBackend/settings.yaml
  - Initialisation/Site/cozybackend/config.yaml
anti_pattern: "TypoScript constants in sys_template records; values duplicated across many TSconfig files."
---
## Solution
`settings.definitions.yaml`:
```yaml
categories:
  MySite: ~
settings:
  tt_content.default.header:
    label: 'Default header for new elements'
    category: MySite
    type: string
    default: 'Headline'
```
Use it in the set's `page.tsconfig`: `TCAdefaults.tt_content.header = {$tt_content.default.header}`.
Activate the set in the site: `dependencies: [my-vendor/my-site]`.

## Pitfalls
- `enum` keys must match the type; use `type: int` for numeric options.
- Without the `dependencies` entry in the site config, nothing from the set is loaded.

## References
- TYPO3 Explained → Site handling → Site sets
