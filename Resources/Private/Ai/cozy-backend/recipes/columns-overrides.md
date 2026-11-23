---
id: columns-overrides
title: Field behaves differently for one content type
editor_need: "Only this element needs the RTE / another label / fewer options for a field."
mechanism: tca-override
typo3: [13, 14]
version_notes: "Unchanged. In v13+ columns defined in TCA get database columns automatically; ext_tables.sql entries for them are optional."
examples:
  - Configuration/TCA/Overrides/tt_content.php
anti_pattern: "Adding a second, almost identical field instead of reusing bodytext with columnsOverrides."
---
## When
Behaviour of an existing field must change for a single CType only.

## Solution
```php
$GLOBALS['TCA']['tt_content']['types']['my_type']['columnsOverrides'] = [
    'bodytext' => ['config' => ['enableRichtext' => true]],
];
```
Labels per type can also be changed in TSconfig: `TCEFORM.tt_content.bodytext.types.my_type.label`.

## Pitfalls
- Only `config` (and `label`, `description`) are meant to be overridden; don't change the field type.
- Keep TCA changes in `Configuration/TCA/Overrides/`, never in `ext_tables.php`.

## References
- TCA Reference → types → columnsOverrides
