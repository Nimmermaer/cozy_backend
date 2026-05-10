<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, string $extensionKey): void {

    ExtensionManagementUtility::addRecordType(
        new SelectItem(
            type: 'select',
            label: 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang.xlf:tt_content.tx_cozybackend_doubletext',
            value: 'tx_cozybackend_doubletext',
            icon: 'content-text-columns',
            group: 'cozyBackend',
            description: 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.tx_cozybackend_doubletext.description',
        ),
        '--div--;core.form.tabs:general, --palette--;;general, --palette--;;headers,
         bodytext,
         alternative_bodytext,
         --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;;frames, --palette--;;appearanceLinks,
         --div--;core.form.tabs:language, --palette--;;language,
         --div--;core.form.tabs:access, --palette--;;hidden, --palette--;;access,
         --div--;core.form.tabs:categories, categories,
         --div--;core.form.tabs:notes, rowDescription,
         --div--;core.form.tabs:extended,',
        [
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
        ],
        'after:bodytext'
    );

}, 'tt_content', 'cozy_backend');
