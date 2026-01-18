<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Queue\Handler;

use Doctrine\DBAL\Exception;
use Mblunck\CozyBackend\Queue\Message\ExportMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TYPO3\CMS\Core\Database\ConnectionPool;

#[AsMessageHandler]
class ExportTrackingHandler
{
    public function __construct(
        protected readonly ConnectionPool $connectionPool
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ExportMessage $message): void
    {
        $connection = $this->connectionPool->getConnectionForTable('tx_cozybackend_domain_model_downloadlog');

        $connection->insert(
            'tx_cozybackend_domain_model_downloadlog',
            [
                'pid' => 1,
                'fe_user' => $message->feUserId ?? 0,
                'referrer' => substr($message->referrer, 0, 255),
                'description' => $message->description,
                'crdate' => $message->tstamp->getTimestamp(),
                'tstamp' => $message->crdate->getTimestamp(),
            ]
        );
    }
}
