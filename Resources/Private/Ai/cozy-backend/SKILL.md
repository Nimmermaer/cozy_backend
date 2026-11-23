---
name: cozy-backend
description: Core-first recipes for building a clean TYPO3 (v13/v14) backend for editors. Use whenever a TYPO3 task touches what editors see or do in the backend - "editors should...", hide/rename/prefill fields, reduce options, add a content element or a variant of one, configure the new content element wizard, backend layouts, page module, page tree, backend branding, site set settings, TSconfig or TCA overrides. Use it BEFORE writing new PHP, Extbase plugins, XCLASSes or custom content elements.
---

# Cozy Backend – core first

Most editor wishes in TYPO3 are solved by **configuration the core already has**:
Page/User TSconfig, TCA overrides, Site Set settings, PSR-14 events, TypoScript and Fluid.
New PHP, new content elements or Extbase are the last resort, not the first idea.

The recipes in `recipes/` are short, tested patterns. Each one points to a working
example file in the `mblunck/cozy-backend` extension (paths relative to the extension root).

## Workflow

1. **State the editor need in one sentence.** ("Editors shouldn't see the date field on text elements.")
2. **Look it up in `recipes/_decision-tree.md`** and read the matching recipe(s).
3. **Inspect the project first.** Find what is already configured before adding anything:
   site sets (`Configuration/Sets/*/`), `page.tsconfig`, `user.tsconfig`, `Configuration/TCA/Overrides/`,
   `settings.definitions.yaml`. Extend existing files instead of creating parallel ones.
4. **Implement the smallest core solution.** Prefer, in this order:
   Site Set setting → Page TSconfig → User TSconfig → TCA override → TypoScript/Fluid → PSR-14 event → new PHP.
   Put configuration into the project's site set, not into `ext_localconf.php`.
5. **Check the TYPO3 version** (`composer.lock` → `typo3/cms-core`) and read `version_notes` of the recipe.
6. **Only if no recipe fits:** write custom code, and say briefly why configuration was not enough.
7. **Explain the result for editors**: what changes in the backend and where it is configured.

## Anti-patterns (do not do this)

- A new content element only to hide fields, change labels or add a visual variant
  → use TCEFORM, `columnsOverrides` or a `layout` item + TypoScript `CASE` (recipes `tceform-hide-rename`,
  `layout-variants`, `columns-overrides`).
- Extbase plugin for plain output of records → TCA + TypoScript + Fluid (`content-element-without-extbase`).
- XCLASS or hooks to add info to backend modules → PSR-14 events (`page-module-event`).
- Hard-coded default values in PHP/DataHandler hooks → `TCAdefaults` (`tcadefaults-prefill`).
- Copy-pasting the same TSconfig into many pages → site set `page.tsconfig` + settings (`site-set-settings`).
- Hard-coded labels in TCA → XLF labels.
- Configuration in `ext_localconf.php` that belongs into a site set or `settings.php`.
- Using options that no longer exist (e.g. wizard `show`, `config.linkVars = L`) – check `version_notes`.

## Recipes

See `recipes/_decision-tree.md` for the index. Every recipe has YAML frontmatter
(`id`, `editor_need`, `mechanism`, `typo3`, `version_notes`, `examples`, `anti_pattern`).
