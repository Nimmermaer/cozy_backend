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
        $view = $this->backendViewFactory->create($request, ['mblunck/cozy-backend']);
        $queryParams = $request->getQueryParams();
        $page = $this->pageRepository->getPage($queryParams['id']);

        if (array_key_exists('language', $queryParams) && $queryParams['language'] > 0) {
            $page = $this->pageRepository->getPageOverlay(
                $queryParams['id'],
                $queryParams['language']
            );
            $page['uid'] = $page['_PAGES_OVERLAY_UID'];
        }

        foreach ($this->imageFields as $variableName => $field) {
            $view->assign(
                $variableName,
                $this->fileRepository->findByRelation('pages', $field, $page['uid'])
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
