---
id: page-module-event
title: Extra information in the page module via PSR-14 event
editor_need: "Editors should see (and edit) page properties like SEO data directly in the page module."
mechanism: event
typo3: [13, 14]
version_notes: "ModifyPageLayoutContentEvent exists since v12. Since v13 listeners can be registered with #[AsEventListener] instead of Services.yaml."
examples:
  - Classes/EventListener/PageModulePreviewEventListener.php
  - Classes/Renderer/PagePropertiesRenderer.php
  - Resources/Private/Templates/Backend/PageProperties.html
anti_pattern: "XCLASS of PageLayoutController or hooks."
---
## Solution
```php
#[AsEventListener('my-site/page-properties')]
final readonly class PageModuleListener
{
    public function __construct(private PagePropertiesRenderer $renderer) {}
    public function __invoke(ModifyPageLayoutContentEvent $event): void
    {
        $event->addHeaderContent($this->renderer->render($event->getRequest()));
    }
}
```
Render with `BackendViewFactory`, edit links with `<be:link.editRecord fields="..."/>`.

## Pitfalls
- The page module can be opened without `id` (tree root) – return an empty string.
- Cast query params to int; with `language > 0` fall back if no translation exists.

## References
- TYPO3 Explained → Events → Backend → ModifyPageLayoutContentEvent
