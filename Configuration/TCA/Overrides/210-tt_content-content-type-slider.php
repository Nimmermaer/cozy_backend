<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, string $extensionKey): void {

    ExtensionManagementUtility::addRecordType(
        new SelectItem(
            'select',
            label: 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang.xlf:tt_content.tx_cozybackend_slider',
            value: 'tx_cozybackend_slider',
            icon: 'content-carousel-image',
            group: 'cozyBackend',
            description: 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:tt_content.tx_cozybackend_slider.description',
        ),
        $GLOBALS['TCA'][$table]['types']['image']['showitem']
    );

}, 'tt_content', 'cozy_backend');
