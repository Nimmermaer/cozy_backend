<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(static function ($extensionKey): void {
    $contentElements = [
        'tx_cozybackend_slider' => 'content-carousel-image',
        'tx_cozybackend_doubletext' => 'content-text-columns',
    ];

    foreach ($contentElements as $contentType => $iconIdentifier) {
        ExtensionManagementUtility::addPlugin(
            [
                'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $contentType,
                $contentType,
                $iconIdentifier,
            ],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
            $extensionKey
        );
    }

    $plugins = [
        'cozy_backend_list' => 'cozy-puzzle',
    ];
    foreach ($plugins as $pluginType => $iconIdentifier) {
        ExtensionManagementUtility::addPlugin(
            [
                'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $pluginType,
                $pluginType,
                $iconIdentifier,
            ],
            ExtensionUtility::PLUGIN_TYPE_PLUGIN,
            $extensionKey
        );
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginType]
            = 'pi_flexform';
    }

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['cozy_backend_list'] = 'pages,layout,select_key,recursive';
    ExtensionManagementUtility::addPiFlexFormValue(
        'cozy_backend_list',
        'FILE:EXT:cozy_backend/Configuration/FlexForms/CozyBackendList.xml'
    );

    ExtensionManagementUtility::addTCAcolumns('tt_content', [
        'alternative_bodytext' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.alternative_bodytext.label',
            'config' => [
                'type' => 'text',
            ],
        ],
    ]);

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'alternative_bodytext',
        'tx_cozybackend_doubletext',
        'after:bodytext'
    );

    $GLOBALS['TCA']['tt_content']['types']['tx_cozybackend_slider'] = $GLOBALS['TCA']['tt_content']['types']['image'];

    $GLOBALS['TCA']['tt_content']['types']['tx_cozybackend_doubletext'] = [
        'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, --palette--;;general, --palette--;;headers,
         bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
         alternative_bodytext,
         --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;;frames, --palette--;;appearanceLinks,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, --palette--;;language,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, --palette--;;hidden, --palette--;;access,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, categories,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes, rowDescription,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
        'columnsOverrides' => [
            'alternative_bodytext' => [
                'config' => [
                    'enableRichtext' => true,
                ],
            ],
            'bodytext' => [
                'config' => [
                    'enableRichtext' => true,
                ],
            ],
        ],
    ];
}, 'cozy_backend');
