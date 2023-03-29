<?php

namespace Mblunck\CozyBackend\UserFunc;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class AbstractUserFunc
{
    public function __construct(
        protected readonly ContentObjectRenderer $cObj,
        private readonly StandaloneView $view
    ) {
    }

    public function getView(string $templateName): StandaloneView
    {
        $this->view->setPartialRootPaths(['EXT:cozy_backend/Resources/Private/Partials/']);
        $this->view->setTemplatePathAndFilename("EXT:cozy_backend/Resources/Private/Templates/{$templateName}.html");
        return $this->view;
    }

    public function getSettings(mixed $piFlexForm): array
    {
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $flexFormArray = $flexFormService->convertFlexFormContentToArray($piFlexForm);
        if (array_key_exists('settings', $flexFormArray)) {
            return $flexFormArray['settings'];
        }
        return [];
    }
}
