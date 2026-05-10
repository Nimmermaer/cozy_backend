<?php

declare(strict_types=1);
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {

    ExtensionUtility::registerPlugin(
        $extensionKey,
        'list',
        'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.cozy_backend_list',
        'cozy-puzzle',
        'cozyBackend',
        'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.cozy_backend_list.description',
        'FILE:EXT:cozy_backend/Configuration/FlexForms/cozy_backend_list.xml',
    );

}, 'tt_content', 'cozy_backend');
