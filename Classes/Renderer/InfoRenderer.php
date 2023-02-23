<?php

namespace Mblunck\CozyBackend\Renderer;

use Google\Service\IAMCredentials\GenerateAccessTokenRequest;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InfoRenderer
{

    public function __construct(
        protected readonly BackendViewFactory $backendViewFactory,
        protected readonly PageRepository $pageRepository,
        protected readonly FileRepository $fileRepository,

    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $pid
     * @param string $returnUrl
     * @return string
     */
    public function showPageProperties(ServerRequestInterface $request, int $pid,string $returnUrl = ''): string
    {
        $backendUser = $this->getBackendUser();
        if ($pid <= 0
            || empty($backendUser->user[$backendUser->userid_column])
            || !$backendUser->check('tables_select', 'pages')
        ) {
            return '';
        }
        $page = $this->pageRepository->getPage($pid);
        $view = $this->backendViewFactory->create($request, ['mblunck/cozy-backend']);
        $images['media'] = $this->fileRepository->findByRelation('pages', 'media', $pid);
        $images['og_image'] = $this->fileRepository->findByRelation('pages', 'og_image', $pid);
        $images['twitter_image'] = $this->fileRepository->findByRelation('pages', 'twitter_image', $pid);
        $view->assignMultiple([
            'page'=> $page,
            'images'=> $images,
            'returnUrl' => $returnUrl,
        ]);
        return $view->render('Backend/PageProperties');
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
