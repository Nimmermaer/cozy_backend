<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;

abstract class UserFuncHelper
{
    public function __construct(
        private readonly ViewFactoryInterface $viewFactory,
    ) {
    }

    public function getView(ServerRequestInterface $request): ViewInterface
    {
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:cozy_backend/Resources/Private/Templates'],
            partialRootPaths: ['EXT:cozy_backend/Resources/Private/Partials'],
            layoutRootPaths: ['EXT:cozy_backend/Resources/Private/Layouts'],
            request: $request,
        );
        return $this->viewFactory->create($viewFactoryData);
    }

    public function getSettings(string $piFlexForm): array
    {
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        return $flexFormService->convertFlexFormContentToArray($piFlexForm);
    }
}
