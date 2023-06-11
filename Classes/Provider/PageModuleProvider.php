<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Provider;

use Mblunck\CozyBackend\Renderer\PagePropertiesRenderer;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;




final class PageModuleProvider
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
