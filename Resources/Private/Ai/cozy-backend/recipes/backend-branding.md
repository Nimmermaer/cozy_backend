---
id: backend-branding
title: Backend branding (login, logo, colours, CSS)
editor_need: "The backend should look like the customer's brand and show which system (dev/live) you are in."
mechanism: system-config
typo3: [13, 14]
version_notes: "Branding options of EXT:backend are extension configuration. v14 ships a reworked backend look – check custom CSS selectors after upgrading (not verified)."
examples:
  - ext_localconf.php
  - Resources/Public/Backend/Css/Dev/Backend.css
anti_pattern: "Overwriting the whole $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend'] array from an extension; patching core CSS files."
---
## Solution
In `config/system/settings.php` (Admin Tools > Settings > Extension Configuration > backend):
`loginLogo`, `loginBackgroundImage`, `loginHighlightColor`, `backendLogo`, `backendFavicon`, `loginFootnote`.

Context-specific backend CSS:
```php
$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['my_site'] =
    'EXT:my_site/Resources/Public/Backend/Css/' . (Environment::getContext()->isDevelopment() ? 'Dev/' : 'Live/');
```

## Pitfalls
- Set single keys, never replace the whole `EXTENSIONS.backend` array.
- A coloured top bar per context (dev/stage/live) prevents editing on the wrong system.

## References
- TYPO3 Explained → Backend customization → Backend login form / Extension configuration of EXT:backend
