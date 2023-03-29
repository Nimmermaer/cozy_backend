<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class ElementListUserFunc extends AbstractUserFunc
{
    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    public function listElements(string $content, array $conf, ServerRequestInterface $request): string
    {
        $view = $this->getView($conf['templateName']);
        $settings = $this->getSettings($this->cObj->data['pi_flexform']);
        $view->assign('data', $this->cObj->data);
        $view->assign('elements', $this->getElements($settings));
        return $view->render();
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    private function getElements(array $settings = []): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $identifiers = [
            'deleted' => 0,
            'hidden' => 0,
        ];

        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        if ($languageAspect > 0) {
            $identifiers += [
                'sys_language_uid' => $languageAspect->get('id'),
            ];
        }
        if (array_key_exists('singlePid', $settings) && $settings['singlePid'] > 0) {
            $identifiers += [
                'pid' => $settings['singlePid'],
            ];
        }

        $sorting = [];
        if (array_key_exists('sorting', $settings)) {
            $sorting = [
                $settings['sorting'] => QueryInterface::ORDER_ASCENDING,
            ];
        }
        return $connectionPool->select(
            ['*'],
            'tt_content',
            $identifiers,
            ['CType'],
            $sorting,
        )->fetchAllAssociative();
    }
}
