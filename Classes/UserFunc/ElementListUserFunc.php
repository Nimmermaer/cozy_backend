<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ElementListUserFunc extends UserFuncHelper
{
    /**
     * Columns an editor may choose for sorting in the FlexForm.
     */
    private const ALLOWED_SORTINGS = ['crdate', 'header'];

    public function __construct(
        ViewFactoryInterface $viewFactory,
        private readonly ConnectionPool $connectionPool,
        private readonly Context $context
    ) {
        parent::__construct($viewFactory);
    }

    /**
     * @throws AspectNotFoundException
     * @throws Exception
     */
    public function listElements(string $content, array $conf, ServerRequestInterface $request): string
    {
        /** @var ContentObjectRenderer $currentContentObject */
        $currentContentObject = $request->getAttribute('currentContentObject');
        $data = $currentContentObject->data;

        $view = $this->getView($request);
        $settings = $this->getSettings($data['pi_flexform'] ?? null);
        $view->assign('data', $data);
        $view->assign('elements', $this->getElements($settings));
        return $view->render($conf['templateName']);
    }

    /**
     * @throws AspectNotFoundException
     * @throws Exception
     */
    private function getElements(array $settings = []): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        // deleted, hidden, starttime, endtime, fe_group and workspaces
        $queryBuilder->setRestrictions(new FrontendRestrictionContainer($this->context));

        $queryBuilder->select('*')->from('tt_content');

        /** @var LanguageAspect $languageAspect */
        $languageAspect = $this->context->getAspect('language');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'sys_language_uid',
                $queryBuilder->createNamedParameter($languageAspect->getContentId(), Connection::PARAM_INT)
            )
        );

        $singlePid = $this->normalizePageUid($settings['singlePid'] ?? 0);
        if ($singlePid > 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($singlePid, Connection::PARAM_INT)
                )
            );
        }

        $sorting = (string)($settings['sorting'] ?? '');
        if (in_array($sorting, self::ALLOWED_SORTINGS, true)) {
            $queryBuilder->orderBy($sorting, 'ASC');
        } else {
            $queryBuilder->orderBy('pid')->addOrderBy('sorting');
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    /**
     * A group field may store "12" or "pages_12" depending on its configuration.
     */
    private function normalizePageUid(mixed $value): int
    {
        if (is_string($value)) {
            $value = preg_replace('/^pages_/', '', explode(',', $value)[0]);
        }
        return (int)$value;
    }
}
