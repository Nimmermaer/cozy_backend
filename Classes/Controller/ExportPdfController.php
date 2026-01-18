<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Controller;

use Mblunck\CozyBackend\Attributes\TrackExport;
use Mblunck\CozyBackend\Service\ChromePdfService;
use Mblunck\CozyBackend\Service\PdfViewService;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExportPdfController extends ActionController
{
    public function __construct(
        protected readonly ChromePdfService $pdfService,
        protected readonly PdfViewService $viewService
    ) {
    }

    #[TrackExport(description: 'Nutzer hat ein PDF generiert')]
    public function downloadAction(): ResponseInterface
    {
        /** @var Site $site */

        /** @var RequestFactory $requestFactory */

        try {
            $site = $this->request->getAttribute('site');
            /** @var Uri $route */
            $route = $site->getRouter()->generateUri(
                $this->request->getArguments()['page'],
                parameters: [
                    'pdf' => 1,
                ],
                type: RouterInterface::ABSOLUTE_URL,
            );
            $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
            $response = $requestFactory->request((string) $route);
            $content = $response->getBody()->getContents();

            if (Environment::getContext()->isDevelopment()) {
                $route = (string) $route
                        |> trim(...)
                        |> (fn ($str) => str_replace($site->getBase()->getHost(), 'web', $str))
                        |> (fn ($str) => str_replace('https://', 'http://', $str));
            }

            $html = $this->viewService->renderTemplate('Export/Pdf', [
                'data' => $content,
            ]);

            $pdfContent = $this->pdfService->generateBinaryPdf($html, $route);

            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/pdf')
                ->withBody($this->streamFactory->createStream($pdfContent));

        } catch (RuntimeException) {
            $this->addFlashMessage(
                'Der PDF-Service ist zurzeit nicht erreichbar. Bitte versuchen Sie es später erneut.',
                'Export fehlgeschlagen',
                ContextualFeedbackSeverity::ERROR
            );

            return $this->redirect('list');
        }
    }
}
