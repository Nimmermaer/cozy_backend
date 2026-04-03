<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Service;

use Exception;
use HeadlessChromium\BrowserFactory;
use Psr\Log\LoggerInterface;
use RuntimeException;
use TYPO3\CMS\Core\Core\Environment;

readonly class ChromePdfService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $chromeBinary = 'chromium'
    ) {
    }

    public function generateBinaryPdf(string $html, string $uri): string
    {
        try {
            $browserFactory = new BrowserFactory($this->chromeBinary);

            $customFlags = [
                '--headless',
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-background-networking',
                '--disable-sync',
                '--disable-gpu',
                '--no-zygote',
                '--disable-dbus',
                '--disable-default-apps',
                '--disable-extensions',
                '--no-first-run',
                '--no-pings',
                '--mute-audio',
            ];

            if (Environment::getContext()->isDevelopment()) {
                $customFlags[] = '--host-rules=MAP ' . $uri . ' 127.0.0.1';
                $customFlags[] = '--ignore-certificate-errors';
                $customFlags[] = '--allow-insecure-localhost';
            }
            $browser = $browserFactory->createBrowser([
                'customFlags' => $customFlags,
            ]);

            try {
                $page = $browser->createPage();
                $page->setHtml($html);
                $pdf = $page->pdf([
                    'printBackground' => true,
                ]);
                return base64_decode((string) $pdf->getBase64(), true);
            } finally {
                $browser->close();
            }
        } catch (Exception $e) {
            $this->logger->error('PDF Generation failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            throw new RuntimeException('PDF konnte nicht erstellt werden.', $e->getCode(), $e);
        }
    }
}
