<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Mblunck\CozyBackend\Provider;

use Mblunck\CozyBackend\Renderer\PagePropertiesRenderer;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;

/**
 * Event listener to render notes in the page module.
 *
 * @internal This is a specific listener implementation and is not considered part of the Public TYPO3 API.
 */
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
