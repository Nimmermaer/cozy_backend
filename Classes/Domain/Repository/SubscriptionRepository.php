<?php

declare(strict_types=1);

/*
 * This file is part of the package mblunck/cozy-backend.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
namespace Mblunck\CozyBackend\Domain\Repository;

use Minishlink\WebPush\Subscription;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @var Repository<\Mblunck\CozyBackend\Domain\Model\Subscription>
 */
final class SubscriptionRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
    /**
     * @throws IllegalObjectTypeException
     */
    public function saveSubscription(Subscription $subscription): void
    {
         $temp = new \Mblunck\CozyBackend\Domain\Model\Subscription();
         $temp
         ->setAuth($subscription->getAuthToken())
         ->setEndpoint($subscription->getEndpoint())
         ->setPublicKey($subscription->getPublicKey())
         ->setContentEncoding($subscription->getContentEncoding());
         $this->persistenceManager->add($temp);
         $this->persistenceManager->persistAll();
    }
}