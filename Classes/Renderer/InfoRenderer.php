<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Renderer;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Resource\FileRepository;

class InfoRenderer
{
    public function __construct(
        protected readonly BackendViewFactory $backendViewFactory,
        protected readonly PageRepository $pageRepository,
        protected readonly FileRepository $fileRepository,
    ) {
    }


    public function showPageProperties(ServerRequestInterface $request, int $pid, string $returnUrl = ''): string
    {
        $images = [];
        $backendUser = $this->getBackendUser();
        if ($pid <= 0
            || empty($backendUser->user[$backendUser->userid_column])
            || ! $backendUser->check('tables_select', 'pages')
        ) {
            return '';
        }
        $page = $this->pageRepository->getPage($pid);
        $view = $this->backendViewFactory->create($request, ['mblunck/cozy-backend']);
        $images['media'] = $this->fileRepository->findByRelation('pages', 'media', $pid);
        $images['og_image'] = $this->fileRepository->findByRelation('pages', 'og_image', $pid);
        $images['twitter_image'] = $this->fileRepository->findByRelation('pages', 'twitter_image', $pid);
        $view->assignMultiple([
            'page' => $page,
            'images' => $images,
            'returnUrl' => $returnUrl,
        ]);
        return $view->render('Backend/PageProperties');
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
