<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;

readonly class PdfViewService
{
    public function __construct(
        protected ViewFactoryInterface $viewFactory
    ) {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function renderTemplate(string $templateName, array $variables = []): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        /** @var SiteSettings $settings */
        $settings = $request->getAttribute('site')->getSettings();
        $viewData = new ViewFactoryData(
            templateRootPaths: [$settings->get('styles.templates.templateRootPath')],
            partialRootPaths: [$settings->get('styles.templates.partialRootPath')],
            layoutRootPaths: [$settings->get('styles.templates.layoutRootPath')],
            request: $request,
        );
        $view = $this->viewFactory->create($viewData);

        $view->assignMultiple($variables);

        return $view->render($templateName);
    }
}
