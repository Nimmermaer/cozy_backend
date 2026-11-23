<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Renderer;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Resource\FileRepository;

class PagePropertiesRenderer
{
    private array $imageFields = [
        'twitterImage' => 'twitter_image',
        'facebookImage' => 'og_image',
        'media' => 'media',
    ];

    public function __construct(
        protected readonly BackendViewFactory $backendViewFactory,
        protected readonly PageRepository $pageRepository,
        protected readonly FileRepository $fileRepository,
    ) {
    }

    public function showPageProperties(ServerRequestInterface $request): string
    {
        $queryParams = $request->getQueryParams();
        $pageId = (int)($queryParams['id'] ?? 0);
        $languageId = (int)($queryParams['language'] ?? 0);

        // No page selected (e.g. the page tree root) or no access: render nothing
        $page = $pageId > 0 ? $this->pageRepository->getPage($pageId) : [];
        if ($page === []) {
            return '';
        }

        if ($languageId > 0) {
            $page = $this->pageRepository->getPageOverlay($page, $languageId);
            // Fall back to the default language record if no translation exists
            $page['uid'] = (int)($page['_LOCALIZED_UID'] ?? $page['uid']);
        }

        $view = $this->backendViewFactory->create($request, ['mblunck/cozy-backend']);

        foreach ($this->imageFields as $variableName => $field) {
            $view->assign(
                $variableName,
                $this->fileRepository->findByRelation('pages', $field, (int)$page['uid'])
            );
        }
        $view->assignMultiple([
            'page' => $page,
            'returnUrl' => $request->getAttribute('normalizedParams')->getRequestUri(),
        ]);
        return $view->render('Backend/PageProperties');
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
