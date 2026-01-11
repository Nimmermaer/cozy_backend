<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {

    $GLOBALS['TCA'][$table]['columns']['CType']['config']['itemGroups']['cozyBackend'] =
        'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:cozy_backend.tt_content.group';

    $contentElements = [
        'tx_cozybackend_slider' => 'content-carousel-image',
        'tx_cozybackend_doubletext' => 'content-text-columns',
    ];

    foreach ($contentElements as $contentType => $iconIdentifier) {
        ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                'label' => 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $contentType,
                'description' => 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $contentType . '.description',
                'value' => $contentType,
                'group' => 'cozyBackend',
                'icon' => $iconIdentifier,
            ],
            'textmedia',
            'after',
        );
        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentType] = $iconIdentifier;
    }

    $plugins = [
        'cozy_backend_list' => 'cozy-puzzle',
    ];
    foreach ($plugins as $pluginType => $iconIdentifier) {
        ExtensionManagementUtility::addPlugin(
            new SelectItem(
                '',
                'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $pluginType,
                $pluginType,
                $iconIdentifier,
                'cozyBackend',
                'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.' . $pluginType . '.description',
            ),
            'FILE:EXT:cozy_backend/Configuration/FlexForms/' . $pluginType . '.xml',
        );
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Configuration,pi_flexform,',
            $pluginType,
            'after:subheader'
        );
    }

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
        'showitem' => '--div--;core.form.tabs:general, --palette--;;general, --palette--;;headers,
         bodytext,
         alternative_bodytext,
         --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;;frames, --palette--;;appearanceLinks,
         --div--;core.form.tabs:language, --palette--;;language,
         --div--;core.form.tabs:access, --palette--;;hidden, --palette--;;access,
         --div--;core.form.tabs:categories, categories,
         --div--;core.form.tabs:notes, rowDescription,
         --div--;core.form.tabs:extended,',
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
}, 'tt_content', 'cozy_backend');
