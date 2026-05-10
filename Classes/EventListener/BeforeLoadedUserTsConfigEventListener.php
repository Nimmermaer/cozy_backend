<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\EventListener;

use Mblunck\CozyBackend\Enum\Doktype;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Event\BeforeLoadedUserTsConfigEvent;

#[AsEventListener(
    identifier: 'cozy-backend/global-usertsconfig',
)]
final readonly class BeforeLoadedUserTsConfigEventListener
{
    public function __invoke(BeforeLoadedUserTsConfigEvent $event): void
    {
        $tsConfig = '';
        foreach (Doktype::cases() as $key) {
            $tsConfig .= "options.pageTree.doktypesToShowInNewPageDragArea := addToList({$key->value}) ";
        }
        $event->addTsConfig($tsConfig);
    }
}
