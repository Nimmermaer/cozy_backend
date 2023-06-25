<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\EventListener;

use Mblunck\CozyBackend\Renderer\PagePropertiesRenderer;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;

/**
 * Event listener to render notes in the page module.
 *
 * @internal This is a specific listener implementation and is not considered part of the Public TYPO3 API.
 */
final class PageModulePreviewEventListener
{
    public function __construct(
        protected readonly PagePropertiesRenderer $pageRenderer
    ) {
    }

    /**
     * Add page properties as visible content to the header of the page module
     */
    public function __invoke(ModifyPageLayoutContentEvent $event): void
    {
        $event->addHeaderContent($this->pageRenderer->showPageProperties($event->getRequest()));
    }
}
