<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Attribute\AsAllowedCallable;
use TYPO3\CMS\Core\Collection\LazyRecordCollection;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

final class ElementListUserFunc extends UserFuncHelper
{
    public function __construct(
        private readonly ViewFactoryInterface $viewFactory,
        private readonly ConnectionPool $connectionPool,
        private readonly Context $context,
        private readonly RecordFactory $recordFactory,
    ) {
        parent::__construct($this->viewFactory);
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    #[AsAllowedCallable]
    public function listElements(string $content, array $conf, ServerRequestInterface $request): string
    {
        $cObj = $request->getAttribute('currentContentObject');
        // @extensionScannerIgnoreLine
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow('tt_content', $cObj->data);

        $settings = $record->get('pi_flexform')->get('settings');
        $currentPageNumber = 1;
        $paginator = new ArrayPaginator($this->getElements($settings), $currentPageNumber, 3);
        $pagination = new SimplePagination($paginator);
        $view = $this->getView($request);
        $view->assignMultiple([
            'data' => $record,
            'paginator' => $pagination,
        ]);

        return $view->render($conf['templateName']);
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    private function getElements(array $settings = []): array
    {

        $connectionPool = $this->connectionPool->getConnectionForTable('tt_content');
        $identifiers = [
            'deleted' => 0,
            'hidden' => 0,
        ];

        $languageAspect = $this->context->getAspect('language');
        if ($languageAspect > 0) {
            $identifiers += [
                'sys_language_uid' => $languageAspect->get('id'),
            ];
        }
        /** @var LazyRecordCollection $lazyRecord */
        $lazyRecord = $settings['singlePid'];
        if (array_key_exists('singlePid', $settings) && (string) $lazyRecord > 0) {
            $identifiers += [
                'pid' => (string) $lazyRecord,
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
            orderBy: $sorting,
        )->fetchAllAssociative();
    }
}
