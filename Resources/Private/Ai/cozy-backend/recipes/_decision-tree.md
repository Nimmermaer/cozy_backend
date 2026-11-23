# Decision tree: editor need → core mechanism

Read top to bottom, take the first match. Recipe ids in backticks.

## Forms and fields
- Field should be **pre-filled** for new records → `tcadefaults-prefill`
- Field should be **hidden, renamed, or have fewer options** → `tceform-hide-rename`
- Field behaves differently **for one CType only** (RTE on/off, other label, other items) → `columns-overrides` (or TCEFORM `types.<CType>` in `tceform-hide-rename`)
- Several defaults should be **configurable per site** without editing TSconfig → `site-set-settings`

## Content elements
- Editors want a **visual variant** of an existing element → `layout-variants`
- Editors want a **pre-configured element** (e.g. text left, image right) → `wizard-presets`
- Editors need a **genuinely new data structure** → `content-element-without-extbase`
- A reusable **piece of markup** (button, card, heading) in templates → `fluid-components`

## Page module and backend UI
- Columns/areas on a page, different per user group → `backend-layouts`
- Show **extra information** in the page module (page properties, hints) → `page-module-event`
- Change how an element **looks in the page module** → `backend-preview-override`
- Copies should not get "(copy)" / not be hidden → `tcemain-copy`
- Page tree colours, page ids, hide modules for users → `user-tsconfig-options`
- Info module columns, recycler, forbid record types in list module → `module-tsconfig`
- Logo, login image, colours, backend CSS → `backend-branding`

## None of the above?
Check the TSconfig reference and the TCA reference first. If configuration really is not enough,
build the smallest custom piece (event listener before XCLASS, TCA+Fluid before Extbase) and
document why.
