<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ElementListUserFunc
{
    private ContentObjectRenderer $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer): void
    {
        $this->cObj = $contentObjectRenderer;
    }

    public function listElements(string $content, array $conf, ServerRequestInterface $request): string
    {
        $view = $this->getView();
        $settings = $this->getSettings($this->cObj->data['pi_flexform']);
        $view->assign('data', $this->cObj->data);
        $view->assign('elements', $this->getElements($settings));
        return $view->render();
    }

    private function getView(): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths(['EXT:cozy_backend/Resources/Private/Templates']);
        $view->setTemplatePathAndFilename('EXT:cozy_backend/Resources/Private/Templates/ListElements.html');
        return $view;
    }

    private function getSettings(mixed $piFlexForm): array
    {
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $flexFormArray = $flexFormService->convertFlexFormContentToArray($piFlexForm);
        if (array_key_exists('settings', $flexFormArray)) {
            return $flexFormArray['settings'];
        }
        return [];
    }

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
