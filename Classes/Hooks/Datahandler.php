<?php

namespace Mblunck\CozyBackend\Hooks;

use Mblunck\CozyBackend\Domain\Repository\SubscriptionRepository;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class Datahandler
{
    public function __construct(
        protected readonly SubscriptionRepository $subscriptionRepository,
    ) {
    }

    /**
     * @throws \ErrorException
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler): void
    {
        if ($table !== 'tt_content' && $status !== 'new') {
            return;
        }
        $subscriptions = $this->subscriptionRepository->findAll();
        $auth = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['cozy_backend'];
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $auth['subject'],
                'publicKey' => $auth['vapidPublicKey'],
                'privateKey' => $auth['vapidPrivateKey'],
            ],
        ]);
        $payload = json_encode([
            'title' => 'Neue News',
            'body' => $fieldArray['bodytext'] ?? '',
            'url' => 'https://typo3v13.ddev.site/site.mobile'
        ]);
        /** @var \Mblunck\CozyBackend\Domain\Model\Subscription $dbSubscription */
        foreach ($subscriptions as $dbSubscription) {
            $subscription = Subscription::create([
                'endpoint' => $dbSubscription->getEndpoint(),
                'publicKey' => $dbSubscription->getPublicKey(),
                'authToken' => $dbSubscription->getAuthToken(),
            ]);
            $webPush->sendOneNotification(
                $subscription,
                $payload
            );
        }
    }
}