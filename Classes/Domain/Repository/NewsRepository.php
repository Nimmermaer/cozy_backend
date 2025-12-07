<?php

namespace Mblunck\CozyBackend\Domain\Repository;

use Mblunck\CozyBackend\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @var Repository<News>
 */
class NewsRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}