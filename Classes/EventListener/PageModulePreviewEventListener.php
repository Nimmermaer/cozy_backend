<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\EventListener;

use Mblunck\CozyBackend\Renderer\PagePropertiesRenderer;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;

final readonly class PageModulePreviewEventListener
{
    public function __construct(
        protected PagePropertiesRenderer $pageRenderer
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
