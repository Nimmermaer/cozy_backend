<?php

namespace Mblunck\CozyBackend\Component;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3Fluid\Fluid\Core\Component\AbstractComponentCollection;
use TYPO3Fluid\Fluid\View\TemplatePaths;

class ComponentCollection extends AbstractComponentCollection
{
    private Site|null $site = null;
    public function getTemplatePaths(): TemplatePaths
    {

        /** @var Site $site */
        $this->site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
        $templatePaths = new TemplatePaths();
        $templatePaths->setTemplateRootPaths([
            ExtensionManagementUtility::extPath('cozy_backend', ($this->site->getSettings()->get('componentsPath'))),
        ]);
        return $templatePaths;
    }
    public function getAdditionalVariables(string $viewHelperName): array
    {
        $designTokens ??= json_decode(file_get_contents( ExtensionManagementUtility::extPath('cozy_backend',$this->site->getSettings()->get('designTokens'))), true);

        return [
            'designTokens' => $designTokens,
        ];
    }

    protected function additionalArgumentsAllowed(string $viewHelperName): bool
    {
        return true;
    }
}