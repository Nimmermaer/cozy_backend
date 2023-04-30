<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class UserFuncHelper
{
    protected ContentObjectRenderer $cObj;

    public function __construct(
        private readonly StandaloneView $view
    ) {
    }

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
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
