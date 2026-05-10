<?php

declare(strict_types=1);
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {

    $GLOBALS['TCA'][$table]['columns']['CType']['config']['itemGroups']['cozyBackend'] =
        'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:cozy_backend.tt_content.group';

    ExtensionManagementUtility::addTCAcolumns('tt_content', [
        'alternative_bodytext' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.alternative_bodytext.label',
            'config' => [
                'type' => 'text',
            ],
        ],
    ]);

}, 'tt_content', 'cozy_backend');
