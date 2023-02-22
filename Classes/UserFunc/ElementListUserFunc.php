<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

        $view->assign('data', $this->cObj->data);
        $view->assign('elements', $this->getElements());
        return $view->render();
    }

    private function getView(): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths(['EXT:cozy_backend/Resources/Private/Templates']);
        $view->setTemplate('ListElements');
        return $view;
    }

    private function getElements(): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        return $connectionPool->select(
            ['*'],
            'tt_content',
            [
                'deleted' => 0,
                'hidden' => 0,
            ],
            ['CType']
        )->fetchAllAssociative();
    }
}
